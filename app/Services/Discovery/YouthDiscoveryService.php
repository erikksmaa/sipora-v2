<?php

namespace App\Services\Discovery;

use App\Models\Activity;
use App\Models\ActivityParticipation;
use App\Models\OrganizationMembership;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final class YouthDiscoveryService
{
    public function __construct(
        private readonly ActivityDiscoveryService $activities,
        private readonly CommunityDiscoveryService $communities,
    ) {}

    /** @return array{activities: Collection, communities: Collection, activity_mode: string, community_mode: string} */
    public function for(User $user, int $limit = 3): array
    {
        $interestSlugs = $user->interests()->pluck('slug');
        $historySlugs = Activity::query()
            ->join('activity_participations', 'activities.id', '=', 'activity_participations.activity_id')
            ->join('activity_categories', 'activities.category_id', '=', 'activity_categories.id')
            ->where('activity_participations.user_id', $user->getKey())
            ->where('activity_participations.registration_status', ActivityParticipation::REGISTRATION_ACCEPTED)
            ->where('activity_participations.completion_status', ActivityParticipation::COMPLETION_COMPLETED)
            ->whereNull('activity_participations.deleted_at')
            ->pluck('activity_categories.slug');
        $contextSlugs = $interestSlugs->merge($historySlugs)->unique()->values();

        $activityQuery = $this->activities->publicQuery();
        if ($contextSlugs->isNotEmpty()) {
            $activityQuery->whereHas('category', fn (Builder $category): Builder => $category->whereIn('slug', $contextSlugs));
        }
        $activitySuggestions = $activityQuery->orderBy('start_at')->limit($limit)->get();
        $activityMode = $contextSlugs->isNotEmpty() && $activitySuggestions->isNotEmpty() ? 'personalized' : 'fallback';

        if ($activitySuggestions->isEmpty()) {
            $activitySuggestions = $this->activities->featured($limit);
        }

        $activitySuggestions->each(function (Activity $activity) use ($interestSlugs, $historySlugs, $activityMode): void {
            $activity->setAttribute('discovery_reason', match (true) {
                $activityMode !== 'personalized' => null,
                $interestSlugs->contains($activity->category->slug) => 'Karena kamu tertarik pada '.$activity->category->name,
                $historySlugs->contains($activity->category->slug) => 'Berdasarkan Activity yang pernah kamu selesaikan',
                default => null,
            });
        });

        $joinedIds = $user->organizationMemberships()
            ->where('membership_status', OrganizationMembership::STATUS_ACTIVE)
            ->pluck('organization_id');
        $communityQuery = $this->communities->publicQuery()->whereNotIn('id', $joinedIds);
        if ($contextSlugs->isNotEmpty()) {
            $communityQuery->whereHas('activities', fn (Builder $activity): Builder => $activity
                ->where('review_status', Activity::REVIEW_APPROVED)
                ->where('publication_status', Activity::PUBLICATION_PUBLISHED)
                ->whereHas('category', fn (Builder $category): Builder => $category->whereIn('slug', $contextSlugs)));
        }
        $communitySuggestions = $communityQuery->orderByDesc('approved_at')->limit($limit)->get();
        $communityMode = $contextSlugs->isNotEmpty() && $communitySuggestions->isNotEmpty() ? 'personalized' : 'fallback';

        if ($communitySuggestions->isEmpty()) {
            $communitySuggestions = $this->communities->publicQuery()
                ->whereNotIn('id', $joinedIds)
                ->orderByDesc('approved_at')
                ->limit($limit)
                ->get();
        }

        return [
            'activities' => $activitySuggestions,
            'communities' => $communitySuggestions,
            'activity_mode' => $activityMode,
            'community_mode' => $communityMode,
        ];
    }
}
