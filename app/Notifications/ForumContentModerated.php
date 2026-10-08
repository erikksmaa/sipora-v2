<?php

namespace App\Notifications;

use App\Models\ForumThread;
use App\Models\User;
use App\Notifications\Channels\SiporaDatabaseChannel;
use Illuminate\Notifications\Notification;

final class ForumContentModerated extends Notification
{
    public function __construct(private readonly ForumThread $thread, private readonly string $action) {}
    public function via(User $notifiable): array { return [SiporaDatabaseChannel::class]; }
    public function toSiporaDatabase(User $notifiable): array
    {
        return ['notification_type' => 'forum_content_moderated', 'title' => 'Moderasi konten Forum',
            'body' => 'Konten Forum kamu telah '.match ($this->action) {
                'hidden' => 'disembunyikan', 'active' => 'ditampilkan kembali',
                'locked' => 'dikunci', 'unlocked' => 'dibuka kembali', default => 'ditinjau',
            }.'.', 'data' => ['thread_id' => $this->thread->uuid()]];
    }
}
