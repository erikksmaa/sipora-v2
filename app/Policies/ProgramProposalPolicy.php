<?php

namespace App\Policies;

use App\Models\OrganizationMembership;
use App\Models\ProgramProposal;
use App\Models\User;

final class ProgramProposalPolicy
{
    public function view(User $user, ProgramProposal $proposal): bool
    {
        return $user->hasRole('verifier') || $this->manages($user, $proposal);
    }

    public function update(User $user, ProgramProposal $proposal): bool
    {
        return $proposal->status === ProgramProposal::STATUS_DRAFT && $this->manages($user, $proposal);
    }

    public function submit(User $user, ProgramProposal $proposal): bool
    {
        return $this->update($user, $proposal);
    }

    public function revise(User $user, ProgramProposal $proposal): bool
    {
        return $proposal->status === ProgramProposal::STATUS_REVISION && $this->manages($user, $proposal);
    }

    public function review(User $user, ProgramProposal $proposal): bool
    {
        return $user->hasRole('verifier') && ! $this->manages($user, $proposal);
    }

    private function manages(User $user, ProgramProposal $proposal): bool
    {
        return OrganizationMembership::query()
            ->where('organization_id', $proposal->program->organization_id)
            ->where('user_id', $user->getKey())
            ->where('membership_status', OrganizationMembership::STATUS_ACTIVE)
            ->whereIn('access_role', [OrganizationMembership::ROLE_LEADER, OrganizationMembership::ROLE_MANAGER])
            ->exists();
    }
}
