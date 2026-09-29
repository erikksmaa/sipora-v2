<?php

namespace App\Policies;

use App\Models\FinancialReport;
use App\Models\OrganizationMembership;
use App\Models\Program;
use App\Models\ProgramLogbook;
use App\Models\ProgramProposal;
use App\Models\User;

final class FinancialReportPolicy
{
    public function view(User $user, FinancialReport $report): bool
    {
        $verifierCanView = $user->hasRole('verifier') && $report->status !== FinancialReport::STATUS_DRAFT;

        return $verifierCanView || $this->manages($user, $report->program);
    }

    public function create(User $user, Program $program): bool
    {
        return $this->manages($user, $program)
            && $program->execution_status === Program::STATUS_RUNNING
            && $program->latestProposal()->value('status') === ProgramProposal::STATUS_APPROVED
            && $program->logbooks()->where('status', ProgramLogbook::STATUS_APPROVED)->exists()
            && ! $program->financialReports()->exists();
    }

    public function update(User $user, FinancialReport $report): bool
    {
        return $report->status === FinancialReport::STATUS_DRAFT
            && $report->program->execution_status === Program::STATUS_RUNNING
            && $this->isLatest($report)
            && $this->manages($user, $report->program);
    }

    public function submit(User $user, FinancialReport $report): bool
    {
        return $this->update($user, $report);
    }

    public function revise(User $user, FinancialReport $report): bool
    {
        return $report->status === FinancialReport::STATUS_REVISION
            && $report->program->execution_status === Program::STATUS_RUNNING
            && $this->isLatest($report)
            && $this->manages($user, $report->program);
    }

    public function review(User $user, FinancialReport $report): bool
    {
        return $user->hasRole('verifier')
            && $report->status === FinancialReport::STATUS_SUBMITTED
            && ! $this->manages($user, $report->program);
    }

    private function isLatest(FinancialReport $report): bool
    {
        return (int) $report->version === (int) $report->program->financialReports()->max('version');
    }

    private function manages(User $user, Program $program): bool
    {
        return OrganizationMembership::query()->where('organization_id', $program->organization_id)->where('user_id', $user->getKey())
            ->where('membership_status', OrganizationMembership::STATUS_ACTIVE)->whereIn('access_role', [OrganizationMembership::ROLE_LEADER, OrganizationMembership::ROLE_MANAGER])->exists();
    }
}
