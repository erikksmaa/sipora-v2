<?php

namespace App\Services\Youth;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\ActivityParticipation;
use App\Models\Organization;
use App\Models\OrganizationMembership;
use App\Models\User;
use App\Models\UserCertificate;
use App\Models\UserIdentity;
use App\Services\Activity\ActivityPassportService;
use App\Services\Discovery\ActivityDiscoveryService;
use App\Services\Discovery\CommunityDiscoveryService;
use App\Services\Discovery\OpportunityDiscoveryService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

final class DevelopmentPathwayService
{
    public function __construct(
        private readonly ActivityPassportService $passport,
        private readonly ActivityDiscoveryService $activities,
        private readonly CommunityDiscoveryService $communities,
        private readonly OpportunityDiscoveryService $opportunities,
    ) {}

    /** @return array<string, mixed> */
    public function for(User $user): array
    {
        $user->loadMissing(['interests', 'skills', 'customPortfolioTags', 'profile', 'identity']);
        $interests = $user->interests->pluck('name')->merge($user->customPortfolioTags->where('kind', 'interest')->pluck('name'))->unique()->values();
        $skills = $user->skills->pluck('name')->merge($user->customPortfolioTags->where('kind', 'skill')->pluck('name'))->unique()->values();
        $completedCount = $this->passport->queryFor($user)->count();
        $history = $this->passport->queryFor($user)->limit(6)->get();
        $activeParticipations = $user->activityParticipations()
            ->where('registration_status', ActivityParticipation::REGISTRATION_ACCEPTED)
            ->where('completion_status', ActivityParticipation::COMPLETION_PENDING)
            ->with(['activity.organization', 'activity.category'])
            ->latest('requested_at')->limit(4)->get();
        $joinedCommunities = $user->organizationMemberships()
            ->where('membership_status', OrganizationMembership::STATUS_ACTIVE)
            ->with('organization')->latest('approved_at')->limit(4)->get();
        $certificateCount = $user->certificates()->where('source_type', UserCertificate::SOURCE_SIPORA)
            ->where('verification_status', UserCertificate::STATUS_VERIFIED)->count();

        $result = [
            'interests' => $interests->all(),
            'skills' => $skills->all(),
            'completedCount' => $completedCount,
            'certificateCount' => $certificateCount,
            'history' => $history,
            'activeParticipations' => $activeParticipations,
            'joinedCommunities' => $joinedCommunities,
            'ready' => $interests->isNotEmpty() && $skills->isNotEmpty(),
            'recommendations' => ['activity' => [], 'community' => [], 'opportunity' => []],
        ];
        if (! $result['ready']) {
            return $result;
        }

        $completedCategories = ActivityCategory::query()->whereHas('activities.participations', fn (Builder $participation) => $participation
            ->where('user_id', $user->getKey())
            ->where('registration_status', ActivityParticipation::REGISTRATION_ACCEPTED)
            ->where('completion_status', ActivityParticipation::COMPLETION_COMPLETED))->pluck('slug')->all();
        $memberships = $user->organizationMemberships()->where('membership_status', OrganizationMembership::STATUS_ACTIVE)
            ->pluck('organization_id')->all();
        $context = compact('interests', 'skills', 'completedCategories', 'memberships');
        $now = now()->format('Y-m-d H:i:s.u');

        $activityQuery = $this->activities->publicQuery()
            ->whereNotNull('published_at')->where('published_at', '<=', $now)
            ->where('execution_status', Activity::EXECUTION_SCHEDULED)
            ->where('start_at', '>', $now)
            ->where(fn (Builder $query) => $query->whereNull('registration_open_at')->orWhere('registration_open_at', '<=', $now))
            ->where(fn (Builder $query) => $query->whereNull('registration_close_at')->orWhere('registration_close_at', '>=', $now))
            ->whereDoesntHave('participations', fn (Builder $query) => $query->where('user_id', $user->getKey()))
            ->withCount(['participations as accepted_count' => fn (Builder $query) => $query->where('registration_status', ActivityParticipation::REGISTRATION_ACCEPTED)])
            ->orderBy('start_at');
        $activityCandidates = $activityQuery->get();
        $activities = $activityCandidates->filter(fn (Activity $activity) => $this->canRegister($user, $activity, $memberships));

        $communities = $this->communities->publicQuery()
            ->whereDoesntHave('memberships', fn (Builder $query) => $query->where('user_id', $user->getKey())
                ->whereIn('membership_status', [OrganizationMembership::STATUS_ACTIVE, OrganizationMembership::STATUS_PENDING]))
            ->orderByDesc('approved_at')->get();

        $opportunities = $this->opportunities->publicQuery()
            ->where(fn (Builder $query) => $query->whereNull('deadline_at')->orWhere('deadline_at', '>=', $now))
            ->where(fn (Builder $query) => $query->whereNull('ends_at')->orWhere('ends_at', '>=', $now))
            ->where(fn (Builder $query) => $query->whereNull('organization_id')->orWhereHas('organization', fn (Builder $organization) => $organization
                ->where('review_status', Organization::REVIEW_APPROVED)
                ->where('operational_status', Organization::OPERATIONAL_ACTIVE)))
            ->orderByRaw('deadline_at IS NULL')->orderBy('deadline_at')->get();

        $result['recommendations'] = [
            'activity' => $this->recommend($activities, $context, 'activity'),
            'community' => $this->recommend($communities, $context, 'community'),
            'opportunity' => $this->recommend($opportunities, $context, 'opportunity'),
        ];

        return $result;
    }

    private function canRegister(User $user, Activity $activity, array $memberships): bool
    {
        if ($activity->requires_identity_verification && $user->identity?->verification_status !== UserIdentity::STATUS_VERIFIED) {
            return false;
        }
        if ($activity->members_only && ! in_array($activity->organization_id, $memberships, true)) {
            return false;
        }
        if ($activity->quota !== null && $activity->accepted_count >= $activity->quota) {
            return false;
        }
        if ($activity->min_age !== null || $activity->max_age !== null) {
            $birthDate = $user->profile?->birth_date;
            if (! $birthDate) {
                return false;
            }
            $age = $birthDate->diffInYears($activity->start_at);
            if (($activity->min_age !== null && $age < $activity->min_age)
                || ($activity->max_age !== null && $age > $activity->max_age)) {
                return false;
            }
        }

        return true;
    }

    /** @return list<array{item:mixed,reasons:list<string>}> */
    private function recommend(Collection $items, array $context, string $type): array
    {
        $matches = $items->map(function ($item) use ($context, $type): ?array {
            $category = $item->category;
            $categoryText = $this->normalize($category?->name ?? '');
            $categorySlug = $category?->slug ?? '';
            $title = $this->normalize($type === 'community' ? $item->name : $item->title);
            $description = $this->normalize($item->description ?? '');
            $reasons = [];
            $categoryInterest = false;
            $skillMatch = false;

            foreach ($context['interests'] as $interest) {
                $tokens = $this->tokens($interest);
                if ($this->matchesCategory($categorySlug, $categoryText, $tokens)) {
                    $reasons[] = 'Kategori sesuai minat '.$interest;
                    $categoryInterest = true;
                } elseif ($this->containsAny($title, $tokens) || $this->containsAny($description, $tokens)) {
                    $reasons[] = 'Membahas minat '.$interest;
                }
            }
            foreach ($context['skills'] as $skill) {
                $tokens = $this->tokens($skill);
                if ($tokens !== [] && ($this->containsAny($title, $tokens) || $this->matchCount($description, $tokens) >= min(2, count($tokens)))) {
                    $reasons[] = 'Terkait keahlian '.$skill;
                    $skillMatch = true;
                }
            }
            if ($type === 'activity' && in_array($categorySlug, $context['completedCategories'], true)) {
                $reasons[] = 'Kategori Activity yang pernah kamu selesaikan';
            }
            if ($type === 'activity' && in_array($item->organization_id, $context['memberships'], true)) {
                $reasons[] = 'Diselenggarakan Community yang kamu ikuti';
            }
            if ($reasons === []) {
                return null;
            }

            return ['item' => $item, 'reasons' => array_slice(array_unique($reasons), 0, 3),
                'priority' => $categoryInterest ? 0 : ($skillMatch ? 1 : 2)];
        })->filter()->values()->all();

        usort($matches, fn (array $left, array $right): int => $left['priority'] <=> $right['priority']
            ?: strcmp($type === 'community' ? $left['item']->name : $left['item']->title,
                $type === 'community' ? $right['item']->name : $right['item']->title));

        return array_map(fn (array $match): array => ['item' => $match['item'], 'reasons' => $match['reasons']], array_slice($matches, 0, 4));
    }

    /** @param list<string> $tokens */
    private function matchesCategory(string $slug, string $name, array $tokens): bool
    {
        return $this->containsAny($this->normalize($slug).' '.$name, $tokens);
    }

    /** @param list<string> $tokens */
    private function containsAny(string $text, array $tokens): bool
    {
        return $this->matchCount($text, $tokens) > 0;
    }

    /** @param list<string> $tokens */
    private function matchCount(string $text, array $tokens): int
    {
        return count(array_filter($tokens, fn (string $token): bool => str_contains(' '.$text.' ', ' '.$token.' ')));
    }

    /** @return list<string> */
    private function tokens(string $value): array
    {
        $stop = ['dan', 'yang', 'untuk', 'dengan', 'atau', 'serta', 'pemuda', 'komunitas', 'manajemen', 'pengelolaan'];

        return array_values(array_unique(array_filter(explode(' ', $this->normalize($value)),
            fn (string $token): bool => mb_strlen($token) >= 3 && ! in_array($token, $stop, true))));
    }

    private function normalize(string $value): string
    {
        return trim(preg_replace('/\s+/', ' ', preg_replace('/[^a-z0-9]+/', ' ', strtolower(Str::ascii($value)))));
    }
}
