<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatMessageReport;
use App\Models\ChatRoom;
use App\Models\Organization;
use App\Services\ChatService;
use App\Services\Community\ManagedCommunityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

final class ChatController extends Controller
{
    public function __construct(private readonly ChatService $chat) {}

    public function global(Request $request)
    {
        return $this->page($request, $this->chat->globalRoom());
    }

    public function community(Request $request, Organization $organization)
    {
        return $this->page($request, $this->chat->communityRoom($request->user(), $organization));
    }

    public function room(Request $request, ChatRoom $room)
    {
        return $this->page($request, $room);
    }

    public function manager(Request $request, Organization $organization, ManagedCommunityService $managed)
    {
        Gate::authorize('manageMemberships', $organization);

        return $this->page($request, $this->chat->communityRoom($request->user(), $organization), ['organization' => $organization, 'managedCommunities' => $managed->forUser($request->user()), 'workspace' => 'manager']);
    }

    private function page(Request $request, ChatRoom $room, array $context = [])
    {
        $initial = $this->chat->listing($request->user(), $room);
        $room->load('community');
        $workspace = $request->user()->hasRole('admin') ? 'admin' : ($request->user()->hasRole('verifier') ? 'verifier' : 'youth');
        $config = ['initial' => $initial, 'messagesUrl' => route('chat.messages', $room), 'sendUrl' => route('chat.send', $room), 'roomUrl' => route('chat.room', $room), 'reasons' => ChatService::REASONS];

        return response()->view('chat.show', [...compact('room', 'workspace', 'config'), ...$context])->header('Cache-Control', 'no-store, private');
    }

    public function messages(Request $request, ChatRoom $room)
    {
        $data = $request->validate(['after' => ['nullable', 'string', 'max:150', 'prohibits:before'], 'before' => ['nullable', 'string', 'max:150']]);

        return response()->json($this->chat->listing($request->user(), $room, $data['after'] ?? null, $data['before'] ?? null))->header('Cache-Control', 'no-store, private');
    }

    public function send(Request $request, ChatRoom $room)
    {
        $value = $request->input('message');
        if (is_string($value)) {
            $request->merge(['message' => preg_replace('/^\s+|\s+$/u', '', $value)]);
        }
        $data = $request->validate(['message' => ['required', 'string', 'max:1500', 'regex:/\S/u'], 'nonce' => ['required', 'uuid'], 'user_id' => ['prohibited'], 'chat_room_id' => ['prohibited']]);

        return response()->json(['message' => $this->chat->send($request->user(), $room, $data['message'], $data['nonce'])])->header('Cache-Control', 'no-store, private');
    }

    public function remove(Request $request, ChatRoom $room, ChatMessage $message)
    {
        $this->chat->remove($request->user(), $room, $message);

        return response()->json(['ok' => true]);
    }

    public function report(Request $request, ChatRoom $room, ChatMessage $message)
    {
        $data = $request->validate(['reason' => ['required', Rule::in(array_keys(ChatService::REASONS))]]);
        $this->chat->report($request->user(), $room, $message, $data['reason']);

        return response()->json(['ok' => true]);
    }

    public function moderation(Request $request)
    {
        $status = $request->validate(['status' => ['nullable', Rule::in(['pending', 'dismissed', 'actioned', 'all'])]])['status'] ?? 'pending';
        $reports = ChatMessageReport::with(['message' => fn ($q) => $q->withCount('reports')->with(['room.community', 'sender:id,name'])])->when($status !== 'all', fn ($q) => $q->where('status', $status))->latest()->paginate(20, ['*'], 'reports_page')->withQueryString();
        $rooms = ChatRoom::with('community')->latest()->paginate(15, ['*'], 'rooms_page')->withQueryString();

        return view('admin.chat.index', compact('reports', 'rooms', 'status'));
    }

    public function resolve(Request $request, ChatMessageReport $report)
    {
        $data = $request->validate(['decision' => ['required', Rule::in(['dismissed', 'actioned'])]]);
        if ($data['decision'] === 'actioned') {
            $this->chat->remove($request->user(), $report->message->room, $report->message);
        } else {
            $this->chat->dismiss($request->user(), $report);
        }

        return back()->with('status', 'Laporan chat telah ditindaklanjuti.');
    }

    public function active(Request $request, ChatRoom $room)
    {
        $data = $request->validate(['active' => ['required', 'boolean']]);
        $this->chat->setActive($request->user(), $room, (bool) $data['active']);

        return back()->with('status', 'Status ruang chat diperbarui.');
    }
}
