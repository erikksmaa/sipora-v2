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
        $latestProposal = $program->relationLoaded('latestProposal') ? $program->getRelation('latestProposal') : $program->latestProposal()->first();
        $latestFinancialReport = $program->relationLoaded('latestFinancialReport') ? $program->getRelation('latestFinancialReport') : $program->latestFinancialReport()->first();
        $hasApprovedLogbook = array_key_exists('has_approved_logbook', $program->getAttributes())
            ? (bool) $program->getAttribute('has_approved_logbook')
            : $program->logbooks()->where('status', ProgramLogbook::STATUS_APPROVED)->exists();
        $reasons = [];

        if ($program->execution_status !== Program::STATUS_RUNNING) {
            $reasons[] = $program->execution_status === Program::STATUS_COMPLETED ? 'Program sudah selesai.' : 'Program harus berstatus berjalan.';
        }
        if ($latestProposal?->status !== ProgramProposal::STATUS_APPROVED) {
            $reasons[] = 'Versi Proposal terbaru belum disetujui.';
        }
        if (! $hasApprovedLogbook) {
            $reasons[] = 'Belum ada Logbook yang disetujui.';
        }
        if ($latestFinancialReport?->status !== FinancialReport::STATUS_APPROVED) {
            $reasons[] = 'Versi E-LPJ terbaru belum disetujui.';
        }

        return ['eligible' => $reasons === [], 'reasons' => $reasons, 'latest_proposal' => $latestProposal, 'latest_financial_report' => $latestFinancialReport];
    }
}
