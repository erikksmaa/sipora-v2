<?php

namespace App\Notifications;

use App\Models\ProgramEvaluation;
use App\Models\User;
use App\Notifications\Channels\SiporaDatabaseChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

final class ProgramEvaluationFinalized extends Notification
{
    use Queueable;

    public function __construct(private readonly ProgramEvaluation $evaluation) {}

    public function via(User $notifiable): array
    {
        return [SiporaDatabaseChannel::class];
    }

    public function toSiporaDatabase(User $notifiable): array
    {
        [$title, $body] = match ($this->evaluation->decision) {
            ProgramEvaluation::DECISION_APPROVED => ['Program selesai', 'Evaluasi akhir Program disetujui dan Program telah diselesaikan.'],
            ProgramEvaluation::DECISION_REVISION => ['Tindak lanjut Program diperlukan', 'Evaluasi akhir Program memerlukan tindak lanjut.'],
            default => ['Evaluasi akhir Program ditolak', 'Evaluasi akhir Program belum dapat disetujui.'],
        };

        return ['notification_type' => 'program_evaluation_finalized', 'title' => $title, 'body' => $body,
            'data' => ['program_id' => $this->evaluation->program->uuid(), 'evaluation_id' => $this->evaluation->uuid(), 'decision' => $this->evaluation->decision]];
    }
}
