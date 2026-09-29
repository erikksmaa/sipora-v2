<?php

namespace App\Notifications;

use App\Models\ProgramLogbook;
use App\Models\User;
use App\Notifications\Channels\SiporaDatabaseChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

final class ProgramLogbookReviewed extends Notification
{
    use Queueable;

    public function __construct(private readonly ProgramLogbook $logbook) {}

    public function via(User $notifiable): array
    {
        return [SiporaDatabaseChannel::class];
    }

    public function toSiporaDatabase(User $notifiable): array
    {
        $approved = $this->logbook->status === ProgramLogbook::STATUS_APPROVED;

        return ['notification_type' => 'program_logbook_reviewed', 'title' => $approved ? 'Logbook disetujui' : 'Revisi Logbook diperlukan',
            'body' => $approved ? 'Logbook Program Anda telah disetujui.' : 'Logbook Program Anda memerlukan revisi.',
            'data' => ['logbook_id' => $this->logbook->uuid(), 'program_id' => $this->logbook->program->uuid(), 'status' => $this->logbook->status]];
    }
}
