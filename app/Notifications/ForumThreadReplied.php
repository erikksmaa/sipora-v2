<?php

namespace App\Notifications;

use App\Models\ForumThread;
use App\Models\User;
use App\Notifications\Channels\SiporaDatabaseChannel;
use Illuminate\Notifications\Notification;

final class ForumThreadReplied extends Notification
{
    public function __construct(private readonly ForumThread $thread) {}

    public function via(User $notifiable): array { return [SiporaDatabaseChannel::class]; }

    public function toSiporaDatabase(User $notifiable): array
    {
        return ['notification_type' => 'forum_thread_replied', 'title' => 'Ada balasan baru di Forum',
            'body' => 'Diskusi yang kamu buat mendapat balasan.', 'data' => ['thread_id' => $this->thread->uuid()]];
    }
}
