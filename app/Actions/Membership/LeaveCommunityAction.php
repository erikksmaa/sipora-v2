<?php

namespace App\Actions\Membership;

use App\Models\Organization;
use App\Models\OrganizationMembership;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class LeaveCommunityAction
{
    public function execute(User $user, Organization $organization): OrganizationMembership
    {
        return DB::transaction(function () use ($user, $organization): OrganizationMembership {
            $membership = OrganizationMembership::query()
                ->where('organization_id', $organization->getKey())
                ->where('user_id', $user->getKey())
                ->where('membership_status', OrganizationMembership::STATUS_ACTIVE)
                ->lockForUpdate()
                ->firstOrFail();

            if ($membership->access_role === OrganizationMembership::ROLE_LEADER) {
                $leaderCount = OrganizationMembership::query()
                    ->where('organization_id', $organization->getKey())
                    ->where('membership_status', OrganizationMembership::STATUS_ACTIVE)
                    ->where('access_role', OrganizationMembership::ROLE_LEADER)
                    ->lockForUpdate()
                    ->count();

                if ($leaderCount <= 1) {
                    throw ValidationException::withMessages([
                        'membership' => ['Leader terakhir tidak dapat meninggalkan komunitas.'],
                    ]);
                }
            }

            $membership->forceFill([
                'membership_status' => OrganizationMembership::STATUS_LEFT,
                'ended_at' => now(),
            ])->save();

            return $membership;
        });
    }
}
