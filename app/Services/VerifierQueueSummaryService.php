<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\FinancialReport;
use App\Models\Organization;
use App\Models\Program;
use App\Models\ProgramLogbook;
use App\Models\ProgramProposal;
use App\Models\ProgramApplication;
use App\Models\User;

final class VerifierQueueSummaryService
{
    /** Indicators reuse the review counters; directory links still open all statuses. */
    public function navigation(User $user, ?array $queues = null): array
    {
        $queues ??= $this->summarize();
        $counts = [];
        foreach (['community-verifications' => 'communities', 'activity-verifications' => 'activities', 'program-proposals' => 'proposals', 'program-monitoring' => 'logbooks', 'financial-reports' => 'financial_reports', 'program-evaluations' => 'evaluations'] as $route => $key) {
            $counts[route('verifier.'.$route.'.index')] = $queues[$key];
        }
        $counts[route('verifier.programs.intake.index')] = ProgramApplication::whereIn('status', ['submitted', 'under_review'])
            ->whereHas('program', fn ($program) => $program->where('execution_status', Program::STATUS_RUNNING)
                ->whereHas('latestProposal', fn ($proposal) => $proposal->where('status', ProgramProposal::STATUS_APPROVED))
                ->whereDoesntHave('organization.memberships', fn ($memberships) => $memberships->where('user_id', $user->getKey())->where('membership_status', 'active')->whereIn('access_role', ['leader', 'manager'])))
            ->count();

        return $counts;
    }

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
