<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\FinancialReport;
use App\Models\Organization;
use App\Models\Program;
use App\Models\ProgramLogbook;
use App\Models\ProgramProposal;

final class VerifierQueueSummaryService
{
    public function summarize(): array
    {
        return [
            'communities' => Organization::query()->where('review_status', Organization::REVIEW_PENDING)->count(),
            'activities' => Activity::query()->where('review_status', Activity::REVIEW_PENDING)->count(),
            'proposals' => ProgramProposal::query()->where('status', ProgramProposal::STATUS_SUBMITTED)->count(),
            'logbooks' => ProgramLogbook::query()->where('status', ProgramLogbook::STATUS_SUBMITTED)->count(),
            'financial_reports' => FinancialReport::query()->where('status', FinancialReport::STATUS_SUBMITTED)->count(),
            'evaluations' => Program::query()->where('execution_status', Program::STATUS_RUNNING)
                ->whereHas('latestProposal', fn ($query) => $query->where('status', ProgramProposal::STATUS_APPROVED))
                ->whereHas('logbooks', fn ($query) => $query->where('status', ProgramLogbook::STATUS_APPROVED))
                ->whereHas('latestFinancialReport', fn ($query) => $query->where('status', FinancialReport::STATUS_APPROVED))
                ->count(),
        ];
    }
}
