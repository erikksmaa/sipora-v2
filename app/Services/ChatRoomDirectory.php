<?php

namespace App\Services;

use App\Models\{Organization, OrganizationMembership, User};
use Illuminate\Database\Eloquent\Builder;

final class ChatRoomDirectory
{
    /** Membership-scoped discovery also applies to Admin and Verifier. */
    public function communities(User $user): Builder
    {
        return Organization::query()
            ->whereHas('memberships', fn ($query) => $query
                ->where('user_id', $user->getKey())
                ->where('membership_status', OrganizationMembership::STATUS_ACTIVE))
            ->orderBy('name');
    }
}
