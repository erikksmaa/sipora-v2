<?php

namespace App\Actions\Manager;

use App\Models\FinancialReport;
use App\Models\Program;
use App\Models\ProgramLogbook;
use App\Models\ProgramProposal;
use App\Models\User;
use App\Services\FinancialReportTotalsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class SubmitFinancialReportAction
{
    public function __construct(private readonly FinancialReportTotalsService $totals) {}

    public function execute(User $actor, FinancialReport $report): FinancialReport
    {
        return DB::transaction(function () use ($actor, $report): FinancialReport {
            $report = FinancialReport::query()->with(['items', 'program.latestProposal'])->whereKey($report->getKey())->lockForUpdate()->firstOrFail();
            $isLatest = $report->version === (int) $report->program->financialReports()->max('version');
            $eligible = $report->status === FinancialReport::STATUS_DRAFT && $isLatest
                && $report->program->execution_status === Program::STATUS_RUNNING
                && $report->program->latestProposal?->status === ProgramProposal::STATUS_APPROVED
                && $report->program->logbooks()->where('status', ProgramLogbook::STATUS_APPROVED)->exists()
                && $report->items->isNotEmpty();
            if (! $eligible) {
                throw ValidationException::withMessages(['report' => ['E-LPJ belum lengkap atau tidak lagi dapat diajukan.']]);
            }

            $report->forceFill(['status' => FinancialReport::STATUS_SUBMITTED, 'submitted_at' => now(), 'reviewed_at' => null, 'reviewed_by' => null])->save();
            $summary = $this->totals->summarize($report);
            activity()->causedBy($actor)->performedOn($report)->event('financial_report_submitted')->withProperties(['financial_report_id' => $report->uuid(), 'program_id' => $report->program->uuid(), 'version' => $report->version, 'realization_total' => $summary['realization_total']])->log('E-LPJ diajukan');

            return $report;
        });
    }
}
