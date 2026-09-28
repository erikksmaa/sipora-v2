<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\UserCertificate;
use App\Notifications\Channels\SiporaDatabaseChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

final class ActivityCertificateIssued extends Notification
{
    use Queueable;

    public function __construct(private readonly UserCertificate $certificate) {}

    public function via(User $notifiable): array
    {
        return [SiporaDatabaseChannel::class];
    }

    public function toSiporaDatabase(User $notifiable): array
    {
        return [
            'notification_type' => 'activity_certificate_issued',
            'title' => 'Sertifikat Activity tersedia',
            'body' => 'Sertifikat untuk '.$this->certificate->name.' telah tersedia.',
            'data' => [
                'certificate_id' => $this->certificate->uuid(),
                'activity_id' => $this->certificate->participation->activity->uuid(),
            ],
        ];
    }
}
