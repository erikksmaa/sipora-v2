<?php

namespace App\Policies;

use App\Models\OrganizationMembership;
use App\Models\Program;
use App\Models\ProgramLogbook;
use App\Models\User;

final class ProgramLogbookPolicy
{
    public function view(User $user, ProgramLogbook $logbook): bool
    {
        $reviewableForVerifier = $user->hasRole('verifier')
            && in_array($logbook->status, [ProgramLogbook::STATUS_SUBMITTED, ProgramLogbook::STATUS_REVISION, ProgramLogbook::STATUS_APPROVED], true);

        return $reviewableForVerifier || $this->manages($user, $logbook->program);
    }

    public function create(User $user, Program $program): bool
    {
        return $program->execution_status === Program::STATUS_RUNNING && $this->manages($user, $program);
    }

    public function update(User $user, ProgramLogbook $logbook): bool
    {
        return $logbook->program->execution_status === Program::STATUS_RUNNING && in_array($logbook->status, [ProgramLogbook::STATUS_DRAFT, ProgramLogbook::STATUS_REVISION], true) && $this->manages($user, $logbook->program);
    }

    public function delete(User $user, ProgramLogbook $logbook): bool
    {
        return $this->update($user, $logbook);
    }

    public function submit(User $user, ProgramLogbook $logbook): bool
    {
        return $this->update($user, $logbook);
    }

    public function review(User $user, ProgramLogbook $logbook): bool
    {
        return $user->hasRole('verifier') && $logbook->status === ProgramLogbook::STATUS_SUBMITTED && ! $this->manages($user, $logbook->program);
    }

    private function manages(User $user, Program $program): bool
    {
        return OrganizationMembership::query()->where('organization_id', $program->organization_id)->where('user_id', $user->getKey())
            ->where('membership_status', OrganizationMembership::STATUS_ACTIVE)->whereIn('access_role', [OrganizationMembership::ROLE_LEADER, OrganizationMembership::ROLE_MANAGER])->exists();
    }
}
