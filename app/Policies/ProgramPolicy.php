<?php

namespace App\Policies;

use App\Models\OrganizationMembership;
use App\Models\Program;
use App\Models\ProgramProposal;
use App\Models\User;

final class ProgramPolicy
{
    public function view(User $user, Program $program): bool
    {
        return $this->manages($user, $program);
    }

    public function update(User $user, Program $program): bool
    {
        return $this->manages($user, $program) && $program->execution_status === Program::STATUS_PLANNED
            && ! $program->proposalCompositionLocked();
    }

    public function startExecution(User $user, Program $program): bool
    {
        return $this->manages($user, $program) && $program->execution_status === Program::STATUS_PLANNED
            && $program->latestProposal()->value('status') === ProgramProposal::STATUS_APPROVED
            && $program->activities()->exists();
    }

    public function configureIntake(User $user, Program $program): bool
    {
        return $this->manages($user, $program)
            && in_array($program->execution_status, [Program::STATUS_PLANNED, Program::STATUS_RUNNING], true);
    }

    public function manageApplications(User $user, Program $program): bool
    {
        // Match existing ownership and non-conflicting Verifier oversight.
        if ($user->hasRole('verifier')) {
            return ! $this->manages($user, $program)
                && $program->latestProposal()->value('status') === ProgramProposal::STATUS_APPROVED;
        }

        return $this->manages($user, $program);
    }

    private function manages(User $user, Program $program): bool
    {
        return OrganizationMembership::query()
            ->where('organization_id', $program->organization_id)
            ->where('user_id', $user->getKey())
            ->where('membership_status', OrganizationMembership::STATUS_ACTIVE)
            ->whereIn('access_role', [OrganizationMembership::ROLE_LEADER, OrganizationMembership::ROLE_MANAGER])
            ->exists();
    }
}
