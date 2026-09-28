<?php

namespace App\Actions\Membership;

use App\Models\OrganizationMembership;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class ChangeCommunityMemberRoleAction
{
    public function execute(User $actor, OrganizationMembership $membership, string $role): OrganizationMembership
    {
        if (! in_array($role, [OrganizationMembership::ROLE_MANAGER, OrganizationMembership::ROLE_MEMBER], true)) {
            throw ValidationException::withMessages(['access_role' => ['Peran organisasi tidak valid.']]);
        }

        return DB::transaction(function () use ($actor, $membership, $role): OrganizationMembership {
            $membership = OrganizationMembership::query()->whereKey($membership->getKey())->lockForUpdate()->firstOrFail();
            if ($membership->membership_status !== OrganizationMembership::STATUS_ACTIVE
                || $membership->access_role === OrganizationMembership::ROLE_LEADER) {
                throw ValidationException::withMessages(['access_role' => ['Peran anggota ini tidak dapat diubah.']]);
            }

            $oldRole = $membership->access_role;
            $membership->access_role = $role;
            $membership->save();

            activity()
                ->causedBy($actor)
                ->performedOn($membership)
                ->event('membership_role_changed')
                ->withProperties(['membership_id' => $membership->uuid(), 'from' => $oldRole, 'to' => $role])
                ->log('Peran anggota komunitas diubah');

            return $membership;
        });
    }
}
