<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\OrganizationMembership;
use App\Models\User;

final class OrganizationPolicy
{
    public function view(User $user, Organization $organization): bool
    {
        return $this->owns($user, $organization);
    }

    public function update(User $user, Organization $organization): bool
    {
        return $this->owns($user, $organization)
            && in_array($organization->review_status, [Organization::REVIEW_DRAFT, Organization::REVIEW_REVISION], true);
    }

    public function submit(User $user, Organization $organization): bool
    {
        return $this->update($user, $organization);
    }

    public function manageMemberships(User $user, Organization $organization): bool
    {
        return $organization->memberships()
            ->where('user_id', $user->getKey())
            ->where('membership_status', OrganizationMembership::STATUS_ACTIVE)
            ->whereIn('access_role', [OrganizationMembership::ROLE_LEADER, OrganizationMembership::ROLE_MANAGER])
            ->exists();
    }

    private function owns(User $user, Organization $organization): bool
    {
        return is_string($organization->created_by_user_id)
            && hash_equals($user->getKey(), $organization->created_by_user_id);
    }
}
