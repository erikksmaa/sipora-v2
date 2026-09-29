<?php

namespace App\Notifications;

use App\Models\ProgramProposal;
use App\Models\User;
use App\Notifications\Channels\SiporaDatabaseChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

final class ProgramProposalReviewed extends Notification
{
    use Queueable;

    public function __construct(private readonly ProgramProposal $proposal) {}

    public function via(User $notifiable): array
    {
        return [SiporaDatabaseChannel::class];
    }

    public function toSiporaDatabase(User $notifiable): array
    {
        $message = match ($this->proposal->status) {
            ProgramProposal::STATUS_APPROVED => 'Proposal Program Anda disetujui.',
            ProgramProposal::STATUS_REVISION => 'Proposal Program Anda memerlukan revisi.',
            default => 'Proposal Program Anda ditolak.',
        };

        return ['notification_type' => 'program_proposal_reviewed', 'title' => 'Hasil verifikasi Proposal Program', 'body' => $message,
            'data' => ['proposal_id' => $this->proposal->uuid(), 'program_id' => $this->proposal->program->uuid(),
                'program_title' => $this->proposal->program->title, 'status' => $this->proposal->status]];
    }
}
