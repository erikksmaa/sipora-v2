<?php

namespace App\Notifications;

use App\Models\FinancialReport;
use App\Models\User;
use App\Notifications\Channels\SiporaDatabaseChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

final class FinancialReportReviewed extends Notification
{
    use Queueable;

    public function __construct(private readonly FinancialReport $report) {}

    public function via(User $notifiable): array
    {
        return [SiporaDatabaseChannel::class];
    }

    public function toSiporaDatabase(User $notifiable): array
    {
        [$title, $body] = match ($this->report->status) {
            FinancialReport::STATUS_APPROVED => ['E-LPJ disetujui', 'Laporan E-LPJ Program Anda telah disetujui.'],
            FinancialReport::STATUS_REJECTED => ['E-LPJ ditolak', 'Laporan E-LPJ Program Anda ditolak.'],
            default => ['Revisi E-LPJ diperlukan', 'Laporan E-LPJ Program Anda memerlukan revisi.'],
        };

        return ['notification_type' => 'financial_report_reviewed', 'title' => $title, 'body' => $body,
            'data' => ['financial_report_id' => $this->report->uuid(), 'program_id' => $this->report->program->uuid(), 'status' => $this->report->status, 'version' => $this->report->version]];
    }
}
