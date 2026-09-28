<?php

namespace App\Policies;

use App\Models\OrganizationMembership;
use App\Models\User;

final class OrganizationMembershipPolicy
{
    public function review(User $user, OrganizationMembership $request): bool
    {
        return $this->managementRole($user, $request) !== null
            && $request->membership_status === OrganizationMembership::STATUS_PENDING
            && ! hash_equals($user->getKey(), $request->user_id);
    }

    public function changeRole(User $user, OrganizationMembership $membership): bool
    {
        return $this->managementRole($user, $membership) === OrganizationMembership::ROLE_LEADER
            && $membership->membership_status === OrganizationMembership::STATUS_ACTIVE
            && $membership->access_role !== OrganizationMembership::ROLE_LEADER;
    }

    public function remove(User $user, OrganizationMembership $membership): bool
    {
        $actorRole = $this->managementRole($user, $membership);

        if ($membership->membership_status !== OrganizationMembership::STATUS_ACTIVE
            || $membership->access_role === OrganizationMembership::ROLE_LEADER
            || hash_equals($user->getKey(), $membership->user_id)) {
            return false;
        }

        return $actorRole === OrganizationMembership::ROLE_LEADER
            || ($actorRole === OrganizationMembership::ROLE_MANAGER && $membership->access_role === OrganizationMembership::ROLE_MEMBER);
    }

    private function managementRole(User $user, OrganizationMembership $target): ?string
    {
        return OrganizationMembership::query()
            ->where('organization_id', $target->organization_id)
            ->where('user_id', $user->getKey())
            ->where('membership_status', OrganizationMembership::STATUS_ACTIVE)
            ->whereIn('access_role', [OrganizationMembership::ROLE_LEADER, OrganizationMembership::ROLE_MANAGER])
            ->value('access_role');
    }
}
