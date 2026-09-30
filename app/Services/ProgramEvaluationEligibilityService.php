<?php

namespace App\Services;

use App\Models\FinancialReport;
use App\Models\Program;
use App\Models\ProgramLogbook;
use App\Models\ProgramProposal;

final class ProgramEvaluationEligibilityService
{
    public function assess(Program $program): array
    {
        $latestProposal = $program->latestProposal()->first();
        $latestFinancialReport = $program->latestFinancialReport()->first();
        $reasons = [];

        if ($program->execution_status !== Program::STATUS_RUNNING) {
            $reasons[] = $program->execution_status === Program::STATUS_COMPLETED ? 'Program sudah selesai.' : 'Program harus berstatus berjalan.';
        }
        if ($latestProposal?->status !== ProgramProposal::STATUS_APPROVED) {
            $reasons[] = 'Versi Proposal terbaru belum disetujui.';
        }
        if (! $program->logbooks()->where('status', ProgramLogbook::STATUS_APPROVED)->exists()) {
            $reasons[] = 'Belum ada Logbook yang disetujui.';
        }
        if ($latestFinancialReport?->status !== FinancialReport::STATUS_APPROVED) {
            $reasons[] = 'Versi E-LPJ terbaru belum disetujui.';
        }

        return ['eligible' => $reasons === [], 'reasons' => $reasons, 'latest_proposal' => $latestProposal, 'latest_financial_report' => $latestFinancialReport];
    }
}
