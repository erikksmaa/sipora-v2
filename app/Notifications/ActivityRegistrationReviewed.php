<?php

namespace App\Notifications;

use App\Models\ActivityParticipation;
use App\Models\User;
use App\Notifications\Channels\SiporaDatabaseChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

final class ActivityRegistrationReviewed extends Notification
{
    use Queueable;

    public function __construct(private readonly ActivityParticipation $participation) {}

    public function via(User $notifiable): array
    {
        return [SiporaDatabaseChannel::class];
    }

    public function toSiporaDatabase(User $notifiable): array
    {
        $accepted = $this->participation->registration_status === ActivityParticipation::REGISTRATION_ACCEPTED;

        return [
            'notification_type' => 'activity_registration_result',
            'title' => $accepted ? 'Pendaftaran Activity diterima' : 'Pendaftaran Activity ditolak',
            'body' => $accepted ? 'Pendaftaran Anda telah diterima oleh pengelola Activity.' : 'Pendaftaran Anda belum dapat diterima.',
            'data' => ['activity_id' => $this->participation->activity->uuid(), 'status' => $this->participation->registration_status],
        ];
    }
}
