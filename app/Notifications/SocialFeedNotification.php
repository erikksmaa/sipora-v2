<?php

namespace App\Notifications;

use App\Models\User;
use App\Notifications\Channels\SiporaDatabaseChannel;
use Illuminate\Notifications\Notification;

final class SocialFeedNotification extends Notification
{
    public function __construct(private readonly string $type, private readonly string $body, private readonly ?string $postId = null) {}

    public function via(User $notifiable): array
    {
        return [SiporaDatabaseChannel::class];
    }

    public function toSiporaDatabase(User $notifiable): array
    {
        return ['notification_type' => $this->type, 'title' => 'Update Social Feed', 'body' => $this->body,
            'data' => ['post_id' => $this->postId]];
    }
}
