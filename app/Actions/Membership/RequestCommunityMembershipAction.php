<?php

namespace App\Actions\Membership;

use App\Models\Organization;
use App\Models\OrganizationMembership;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class RequestCommunityMembershipAction
{
    public function execute(User $user, Organization $organization): OrganizationMembership
    {
        return DB::transaction(function () use ($user, $organization): OrganizationMembership {
            $organization = Organization::query()->whereKey($organization->getKey())->lockForUpdate()->firstOrFail();
            if ($organization->review_status !== Organization::REVIEW_APPROVED
                || $organization->operational_status !== Organization::OPERATIONAL_ACTIVE) {
                throw ValidationException::withMessages(['membership' => ['Komunitas ini tidak menerima permintaan bergabung.']]);
            }

            $membership = OrganizationMembership::withTrashed()
                ->where('organization_id', $organization->getKey())
                ->where('user_id', $user->getKey())
                ->lockForUpdate()
                ->first();

            if ($membership && in_array($membership->membership_status, [OrganizationMembership::STATUS_PENDING, OrganizationMembership::STATUS_ACTIVE], true)) {
                throw ValidationException::withMessages(['membership' => ['Permintaan atau keanggotaan aktif sudah tersedia.']]);
            }

            $membership ??= new OrganizationMembership;
            if ($membership->trashed()) {
                $membership->restore();
            }
            $membership->forceFill([
                'organization_id' => $organization->getKey(),
                'user_id' => $user->getKey(),
                'access_role' => OrganizationMembership::ROLE_MEMBER,
                'position_title' => null,
                'membership_status' => OrganizationMembership::STATUS_PENDING,
                'requested_at' => now(),
                'approved_at' => null,
                'approved_by' => null,
                'ended_at' => null,
            ])->save();

            return $membership;
        });
    }
}
