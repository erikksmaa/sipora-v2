<?php

namespace App\Notifications\Channels;

use App\Models\User;
use App\Models\UserNotification;
use App\Support\BinaryUuid;
use Illuminate\Notifications\Notification;

final class SiporaDatabaseChannel
{
    public function send(User $notifiable, Notification $notification): UserNotification
    {
        /** @var array{notification_type:string,title:string,body:string,data:array<string,mixed>} $message */
        $message = $notification->toSiporaDatabase($notifiable);
        // The approved schema requires a JSON object, including when no
        // contextual identifiers are needed by the notification.
        $message['data'] = (object) ($message['data'] ?? []);

        return UserNotification::create([
            'id' => BinaryUuid::generate(),
            'user_id' => $notifiable->getKey(),
            ...$message,
        ]);
    }
}
