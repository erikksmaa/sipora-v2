<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\ActivityParticipation;
use App\Models\Opportunity;
use App\Models\Organization;
use App\Models\OrganizationMembership;
use App\Models\Program;
use App\Models\User;
use App\Models\UserIdentity;

final class AdminAnalyticsService
{
    public function __construct(private readonly YouthStatisticsService $youthStatistics) {}

    public function overview(?array $youthTotals = null): array
    {
        $youthTotals ??= $this->youthStatistics->summary();
        return [
            'youth' => [
                'registered' => $youthTotals['registered'],
                'with_profile' => User::role('youth')->whereHas('profile')->count(),
                'identity' => UserIdentity::query()->selectRaw('verification_status, COUNT(*) AS total')->groupBy('verification_status')->pluck('total', 'verification_status')->map(fn ($value): int => (int) $value)->all(),
            ],
            'communities' => [
                'active' => Organization::query()->where('review_status', Organization::REVIEW_APPROVED)->where('operational_status', Organization::OPERATIONAL_ACTIVE)->count(),
                'pending' => Organization::query()->where('review_status', Organization::REVIEW_PENDING)->count(),
                'active_memberships' => OrganizationMembership::query()->where('membership_status', OrganizationMembership::STATUS_ACTIVE)->count(),
            ],
            'activities' => [
                'published' => Activity::query()->where('review_status', Activity::REVIEW_APPROVED)->where('publication_status', Activity::PUBLICATION_PUBLISHED)->count(),
                'upcoming' => Activity::query()->where('review_status', Activity::REVIEW_APPROVED)->where('publication_status', Activity::PUBLICATION_PUBLISHED)->where('end_at', '>=', now())->count(),
                'registrations' => ActivityParticipation::query()->count(),
                'completed_participations' => ActivityParticipation::query()->where('registration_status', ActivityParticipation::REGISTRATION_ACCEPTED)->where('completion_status', ActivityParticipation::COMPLETION_COMPLETED)->count(),
            ],
            'programs' => Program::query()->selectRaw('execution_status, COUNT(*) AS total')->groupBy('execution_status')->pluck('total', 'execution_status')->map(fn ($value): int => (int) $value)->all(),
            'opportunities' => [
                'published' => Opportunity::query()->where('publication_status', Opportunity::STATUS_PUBLISHED)->where('published_at', '<=', now())->count(),
                'open_deadlines' => Opportunity::query()->where('publication_status', Opportunity::STATUS_PUBLISHED)->where('published_at', '<=', now())->where(fn ($query) => $query->whereNull('deadline_at')->orWhere('deadline_at', '>=', now()))->count(),
            ],
        ];
    }
}
