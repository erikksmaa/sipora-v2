<?php

namespace App\Notifications;

use App\Models\Organization;
use App\Models\User;
use App\Notifications\Channels\SiporaDatabaseChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

final class CommunityMembershipReviewed extends Notification
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
        $accepted = $this->decision === 'accepted';

        return [
            'notification_type' => 'community_membership_result',
            'title' => $accepted ? 'Permintaan bergabung diterima' : 'Permintaan bergabung ditolak',
            'body' => $accepted
                ? 'Anda sekarang menjadi anggota '.$this->organization->name.'.'
                : 'Permintaan Anda untuk bergabung dengan '.$this->organization->name.' ditolak.',
            'data' => ['organization_id' => $this->organization->uuid(), 'decision' => $this->decision],
        ];
    }
}
