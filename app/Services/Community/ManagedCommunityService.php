<?php

namespace App\Services\Community;

use App\Models\Organization;
use App\Models\OrganizationMembership;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

final class ManagedCommunityService
{
    /** @return Collection<int, Organization> */
    public function forUser(User $user): Collection
    {
        return Organization::query()
            ->whereHas('memberships', fn ($query) => $query
                ->where('user_id', $user->getKey())
                ->where('membership_status', OrganizationMembership::STATUS_ACTIVE)
                ->whereIn('access_role', [OrganizationMembership::ROLE_LEADER, OrganizationMembership::ROLE_MANAGER]))
            ->orderBy('name')
            ->get();
    }
}
