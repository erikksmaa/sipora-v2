<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\UserIdentityVerification;
use App\Notifications\Channels\SiporaDatabaseChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

final class IdentityVerificationReviewed extends Notification
{
    use Queueable;

    public function __construct(
        private readonly UserIdentityVerification $submission,
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
            UserIdentityVerification::STATUS_VERIFIED => ['Identitas terverifikasi', 'Verifikasi identitas Anda telah disetujui.'],
            UserIdentityVerification::STATUS_REVISION => ['Revisi identitas diperlukan', 'Verifikasi identitas Anda memerlukan revisi.'],
            default => ['Verifikasi identitas ditolak', 'Pengajuan verifikasi identitas Anda ditolak.'],
        };

        return [
            'notification_type' => 'identity_verification_result',
            'title' => $title,
            'body' => $body,
            'data' => ['submission_id' => $this->submission->uuid(), 'status' => $this->decision],
        ];
    }
}
