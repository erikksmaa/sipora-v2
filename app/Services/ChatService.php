<?php

namespace App\Services;

use App\Models\ChatMessage;
use App\Models\ChatMessageReport;
use App\Models\ChatRoom;
use App\Models\Organization;
use App\Models\User;
use App\Policies\ChatRoomPolicy;
use App\Support\BinaryUuid;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;

final class ChatService
{
    public const REASONS = ['spam' => 'Spam', 'inappropriate' => 'Konten tidak pantas', 'harassment' => 'Pelecehan', 'misinformation' => 'Informasi menyesatkan', 'other' => 'Lainnya'];

    public function __construct(private readonly ChatRoomPolicy $policy) {}

    public function globalRoom(): ChatRoom
    {
        return ChatRoom::firstOrCreate(['type' => 'global'], ['name' => 'Chat Global']);
    }

    public function communityRoom(User $user, Organization $organization): ChatRoom
    {
        $candidate = new ChatRoom(['type' => 'community', 'community_id' => $organization->getKey()]);
        abort_unless($this->policy->view($user, $candidate), 403);

        return ChatRoom::firstOrCreate(['community_id' => $organization->getKey()], ['type' => 'community']);
    }

    // Room locks serialize mutations and establish a committed snapshot for cursors.
    // This prevents missing a send/delete whose transaction commits after a poll.
    public function listing(User $user, ChatRoom $room, ?string $after = null, ?string $before = null): array
    {
        return DB::transaction(function () use ($user, $room, $after, $before) {
            $room = ChatRoom::whereKey($room->getKey())->sharedLock()->firstOrFail();
            abort_unless($this->policy->view($user, $room), 403);
            $query = ChatMessage::withTrashed()->where('chat_room_id', $room->getKey());
            $field = $after ? 'updated_at' : 'created_at';
            if ($after || $before) {
                [$at, $id] = $this->decode($after ?? $before);
                $op = $after ? '>' : '<';
                $query->where(fn ($q) => $q->where($field, $op, $at)->orWhere(fn ($q) => $q->where($field, $at)->where('id', $op, $id)));
            }
            $limit = $after ? 50 : 30;
            $rows = $query->with(['sender:id,name', 'sender.profile', 'sender.profileVisibility'])
                ->orderBy($field, $after ? 'asc' : 'desc')->orderBy('id', $after ? 'asc' : 'desc')->limit($limit + 1)->get();
            $more = $rows->count() > $limit;
            $rows = $rows->take($limit);
            $cursor = $after ? ($rows->last() ? $this->cursor($rows->last()->updated_at, $rows->last()->uuid()) : $after)
                : $this->cursor($room->updated_at, 'ffffffff-ffff-7fff-bfff-ffffffffffff');
            if (! $after) {
                $rows = $rows->reverse()->values();
            }
            $moderator = $this->policy->moderate($user, $room);

            return [
                'messages' => $rows->map(fn ($m) => $this->project($m, $user, $moderator))->values()->all(),
                'cursor' => $cursor, 'before' => ! $after && $rows->first() ? $this->cursor($rows->first()->created_at, $rows->first()->uuid()) : null,
                'has_more' => $more, 'can_send' => $this->policy->send($user, $room),
            ];
        });
    }

    public function send(User $user, ChatRoom $room, string $message, string $nonce): array
    {
        return DB::transaction(function () use ($user, $room, $message, $nonce) {
            $room = ChatRoom::whereKey($room->getKey())->lockForUpdate()->firstOrFail();
            abort_unless($this->policy->send($user, $room), 403);
            $nonceBytes = Uuid::fromString($nonce)->getBytes();
            $existing = ChatMessage::withTrashed()->where('chat_room_id', $room->getKey())->where('user_id', $user->getKey())->where('client_nonce', $nonceBytes)->first();
            if ($existing) {
                return $this->project($existing->load(['sender.profile', 'sender.profileVisibility']), $user, $this->policy->moderate($user, $room));
            }
            $time = $this->tick($room);
            $model = new ChatMessage(['chat_room_id' => $room->getKey(), 'user_id' => $user->getKey(), 'client_nonce' => $nonceBytes, 'message' => $message]);
            $model->created_at = $time;
            $model->updated_at = $time;
            $model->save();

            return $this->project($model->load(['sender.profile', 'sender.profileVisibility']), $user, $this->policy->moderate($user, $room));
        }, 3);
    }

    public function remove(User $user, ChatRoom $room, ChatMessage $message): void
    {
        DB::transaction(function () use ($user, $room, $message) {
            $room = ChatRoom::whereKey($room->getKey())->lockForUpdate()->firstOrFail();
            abort_unless($this->policy->view($user, $room), 403);
            abort_unless($message->chat_room_id === $room->getKey(), 404);
            abort_unless($message->user_id === $user->getKey() || $this->policy->moderate($user, $room), 403);
            $message = ChatMessage::withTrashed()->whereKey($message->getKey())->lockForUpdate()->firstOrFail();
            if ($message->trashed()) {
                return;
            }
            $time = $this->tick($room);
            $message->forceFill(['deleted_at' => $time, 'updated_at' => $time])->save(['timestamps' => false]);
            $count = ChatMessageReport::where('chat_message_id', $message->getKey())->where('status', 'pending')->update(['status' => 'actioned', 'reviewed_by' => $user->getKey(), 'reviewed_at' => $time, 'updated_at' => $time]);
            activity('chat_moderation')->causedBy($user)->performedOn($message)->event('chat_message_deleted')->withProperties(['reports_actioned' => $count])->log('Pesan chat dihapus');
            if ($count > 0) {
                activity('chat_moderation')->causedBy($user)->performedOn($message)->event('chat_reports_actioned')->withProperties(['count' => $count])->log('Laporan pesan chat ditindaklanjuti');
            }
        }, 3);
    }

    public function report(User $user, ChatRoom $room, ChatMessage $message, string $reason): void
    {
        DB::transaction(function () use ($user, $room, $message, $reason) {
            $room = ChatRoom::whereKey($room->getKey())->lockForUpdate()->firstOrFail();
            abort_unless($this->policy->view($user, $room), 403);
            abort_unless($message->chat_room_id === $room->getKey(), 404);
            abort_if(ChatMessage::withTrashed()->findOrFail($message->getKey())->trashed() || $message->user_id === $user->getKey(), 403);
            ChatMessageReport::firstOrCreate(['chat_message_id' => $message->getKey(), 'reporter_id' => $user->getKey()], ['reason' => $reason]);
        }, 3);
    }

    public function dismiss(User $admin, ChatMessageReport $report): void
    {
        abort_unless($admin->hasRole('admin'), 403);
        DB::transaction(function () use ($admin, $report) {
            $report = ChatMessageReport::whereKey($report->getKey())->lockForUpdate()->firstOrFail();
            if ($report->status !== 'pending') {
                return;
            }
            $report->update(['status' => 'dismissed', 'reviewed_by' => $admin->getKey(), 'reviewed_at' => now()]);
            activity('chat_moderation')->causedBy($admin)->performedOn($report)->event('chat_report_dismissed')->log('Laporan chat ditutup');
        });
    }

    public function setActive(User $admin, ChatRoom $room, bool $active): void
    {
        abort_unless($admin->hasRole('admin'), 403);
        DB::transaction(function () use ($admin, $room, $active) {
            $room = ChatRoom::whereKey($room->getKey())->lockForUpdate()->firstOrFail();
            $room->is_active = $active;
            $this->tick($room);
            activity('chat_moderation')->causedBy($admin)->performedOn($room)->event('chat_room_status_changed')->withProperties(['active' => $active])->log('Status ruang chat diperbarui');
        });
    }

    private function project(ChatMessage $m, User $user, bool $moderator): array
    {
        $public = $m->sender->profileVisibility?->is_profile_public && $m->sender->profile;
        $deleted = $m->trashed();

        return ['id' => $m->uuid(), 'name' => $m->sender->name,
            'profile_url' => $public ? route('portfolio.show', $m->sender->profile) : null,
            'avatar_url' => $public && $m->sender->profileVisibility->show_photo && $m->sender->profile->profile_photo_path ? route('portfolio.photo', $m->sender->profile) : null,
            'message' => $deleted ? null : $m->message, 'deleted' => $deleted,
            'created_at' => $m->created_at->toIso8601String(), 'sort_at' => $m->created_at->format('Y-m-d H:i:s.u'),
            'own' => $m->user_id === $user->getKey(), 'can_delete' => ! $deleted && ($moderator || $m->user_id === $user->getKey()),
            'can_report' => ! $deleted && $m->user_id !== $user->getKey()];
    }

    private function tick(ChatRoom $room)
    {
        $time = now()->max($room->updated_at->copy()->addMicrosecond());
        $room->updated_at = $time;
        $room->save();

        return $time;
    }

    private function cursor($at, string $id): string
    {
        return rtrim(strtr(base64_encode($at->format('Y-m-d H:i:s.u').'|'.$id), '+/', '-_'), '=');
    }

    private function decode(string $cursor): array
    {
        try {
            $parts = explode('|', base64_decode(strtr($cursor, '-_', '+/'), true) ?: '', 2);
            if (count($parts) !== 2) {
                throw new \InvalidArgumentException;
            }
            [$at, $id] = $parts;
            if (! preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d{6}$/D', $at) || CarbonImmutable::createFromFormat('Y-m-d H:i:s.u', $at)->format('Y-m-d H:i:s.u') !== $at) {
                throw new \InvalidArgumentException;
            }

            return [$at, BinaryUuid::bytes($id)];
        } catch (\Throwable) {
            throw ValidationException::withMessages(['cursor' => 'Cursor pesan tidak valid. Muat ulang ruang chat.']);
        }
    }
}
