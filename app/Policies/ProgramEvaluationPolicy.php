<?php

namespace App\Policies;

use App\Models\OrganizationMembership;
use App\Models\Program;
use App\Models\ProgramEvaluation;
use App\Models\User;
use App\Services\ProgramEvaluationEligibilityService;

final class ProgramEvaluationPolicy
{
    public function __construct(private readonly ProgramEvaluationEligibilityService $eligibility) {}

    public function view(User $user, Program $program): bool
    {
        return $user->hasRole('verifier') || $this->manages($user, $program);
    }

    public function create(User $user, Program $program): bool
    {
        return $user->hasRole('verifier') && ! $this->manages($user, $program) && $this->eligibility->assess($program)['eligible'];
    }

    public function viewRecord(User $user, ProgramEvaluation $evaluation): bool
    {
        return $this->view($user, $evaluation->program);
    }

    private function manages(User $user, Program $program): bool
    {
        return OrganizationMembership::query()->where('organization_id', $program->organization_id)->where('user_id', $user->getKey())
            ->where('membership_status', OrganizationMembership::STATUS_ACTIVE)->whereIn('access_role', [OrganizationMembership::ROLE_LEADER, OrganizationMembership::ROLE_MANAGER])->exists();
    }
}
