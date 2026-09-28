<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\OrganizationMembership;
use App\Models\User;

final class ActivityPolicy
{
    public function view(User $user, Activity $activity): bool
    {
        return $this->manages($user, $activity);
    }

    public function update(User $user, Activity $activity): bool
    {
        return $this->manages($user, $activity)
            && $activity->publication_status === Activity::PUBLICATION_UNPUBLISHED
            && in_array($activity->review_status, [Activity::REVIEW_DRAFT, Activity::REVIEW_REVISION], true);
    }

    public function submit(User $user, Activity $activity): bool
    {
        return $this->update($user, $activity);
    }

    public function publish(User $user, Activity $activity): bool
    {
        return $this->manages($user, $activity)
            && $activity->review_status === Activity::REVIEW_APPROVED
            && $activity->publication_status === Activity::PUBLICATION_UNPUBLISHED;
    }

    public function archive(User $user, Activity $activity): bool
    {
        return $this->manages($user, $activity)
            && $activity->publication_status === Activity::PUBLICATION_PUBLISHED;
    }

    private function manages(User $user, Activity $activity): bool
    {
        return OrganizationMembership::query()
            ->where('organization_id', $activity->organization_id)
            ->where('user_id', $user->getKey())
            ->where('membership_status', OrganizationMembership::STATUS_ACTIVE)
            ->whereIn('access_role', [OrganizationMembership::ROLE_LEADER, OrganizationMembership::ROLE_MANAGER])
            ->exists();
    }
}
