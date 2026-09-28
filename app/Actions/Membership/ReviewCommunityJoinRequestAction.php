<?php

namespace App\Actions\Membership;

use App\Models\OrganizationMembership;
use App\Models\User;
use App\Notifications\CommunityMembershipReviewed;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class ReviewCommunityJoinRequestAction
{
    public function execute(User $reviewer, OrganizationMembership $request, string $decision): OrganizationMembership
    {
        if (! in_array($decision, ['accepted', 'rejected'], true)) {
            throw ValidationException::withMessages(['decision' => ['Keputusan permintaan bergabung tidak valid.']]);
        }

        return DB::transaction(function () use ($reviewer, $request, $decision): OrganizationMembership {
            $request = OrganizationMembership::query()->whereKey($request->getKey())->lockForUpdate()->firstOrFail();
            if ($request->membership_status !== OrganizationMembership::STATUS_PENDING) {
                throw ValidationException::withMessages(['decision' => ['Permintaan ini sudah diputuskan.']]);
            }
            if (hash_equals($reviewer->getKey(), $request->user_id)) {
                abort(403);
            }

            $accepted = $decision === 'accepted';
            $request->forceFill([
                'access_role' => OrganizationMembership::ROLE_MEMBER,
                'membership_status' => $accepted ? OrganizationMembership::STATUS_ACTIVE : OrganizationMembership::STATUS_REJECTED,
                'approved_at' => $accepted ? now() : null,
                'approved_by' => $reviewer->getKey(),
                'ended_at' => $accepted ? null : now(),
            ])->save();

            activity()
                ->causedBy($reviewer)
                ->performedOn($request)
                ->event($accepted ? 'join_request_accepted' : 'join_request_rejected')
                ->withProperties(['organization_id' => $request->organization->uuid(), 'membership_id' => $request->uuid()])
                ->log('Keputusan permintaan bergabung komunitas');

            $request->user->notify(new CommunityMembershipReviewed($request->organization, $decision));

            return $request;
        });
    }
}
