<?php

namespace App\Actions\Membership;

use App\Models\OrganizationMembership;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class RemoveCommunityMemberAction
{
    public function execute(User $actor, OrganizationMembership $membership): OrganizationMembership
    {
        return DB::transaction(function () use ($actor, $membership): OrganizationMembership {
            $membership = OrganizationMembership::query()->whereKey($membership->getKey())->lockForUpdate()->firstOrFail();
            if ($membership->membership_status !== OrganizationMembership::STATUS_ACTIVE
                || $membership->access_role === OrganizationMembership::ROLE_LEADER) {
                throw ValidationException::withMessages(['membership' => ['Anggota ini tidak dapat dikeluarkan.']]);
            }

            $membership->forceFill([
                'membership_status' => OrganizationMembership::STATUS_REMOVED,
                'ended_at' => now(),
            ])->save();

            activity()
                ->causedBy($actor)
                ->performedOn($membership)
                ->event('membership_removed')
                ->withProperties(['membership_id' => $membership->uuid()])
                ->log('Anggota dikeluarkan dari komunitas');

            return $membership;
        });
    }
}
