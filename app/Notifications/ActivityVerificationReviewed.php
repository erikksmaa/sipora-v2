<?php

namespace App\Notifications;

use App\Models\Activity;
use App\Models\User;
use App\Notifications\Channels\SiporaDatabaseChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

final class ActivityVerificationReviewed extends Notification
{
    use Queueable;

    public function __construct(private readonly Activity $activity, private readonly string $decision) {}

    public function via(User $notifiable): array
    {
        return [SiporaDatabaseChannel::class];
    }

    public function toSiporaDatabase(User $notifiable): array
    {
        [$title, $body] = match ($this->decision) {
            Activity::REVIEW_APPROVED => ['Activity disetujui', 'Usulan Activity Anda disetujui dan siap dipublikasikan.'],
            Activity::REVIEW_REVISION => ['Revisi Activity diperlukan', 'Usulan Activity Anda memerlukan revisi.'],
            default => ['Activity ditolak', 'Usulan Activity Anda ditolak.'],
        };

        return ['notification_type' => 'activity_verification_result', 'title' => $title, 'body' => $body,
            'data' => ['activity_id' => $this->activity->uuid(), 'status' => $this->decision]];
    }
}
