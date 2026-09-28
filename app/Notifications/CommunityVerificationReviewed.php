<?php

namespace App\Notifications;

use App\Models\Organization;
use App\Models\User;
use App\Notifications\Channels\SiporaDatabaseChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

final class CommunityVerificationReviewed extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Organization $organization,
        private readonly string $decision,
    ) {}

    /** @return list<class-string> */
    public function via(User $notifiable): array
    {
        return [SiporaDatabaseChannel::class];
    }

    /** @return array{notification_type:string,title:string,body:string,data:array<string,mixed>} */
    public function toSiporaDatabase(User $notifiable): array
    {
        [$title, $body] = match ($this->decision) {
            Organization::REVIEW_APPROVED => ['Komunitas disetujui', 'Pengajuan komunitas Anda telah disetujui dan diaktifkan.'],
            Organization::REVIEW_REVISION => ['Revisi komunitas diperlukan', 'Pengajuan komunitas Anda memerlukan revisi.'],
            default => ['Pengajuan komunitas ditolak', 'Pengajuan komunitas Anda ditolak.'],
        };

        return [
            'notification_type' => 'community_verification_result',
            'title' => $title,
            'body' => $body,
            'data' => ['organization_id' => $this->organization->uuid(), 'status' => $this->decision],
        ];
    }
}
