<?php

namespace App\Services\Youth;

use App\Models\ActivityParticipation;
use App\Models\Organization;
use App\Models\OrganizationMembership;
use App\Models\UserCertificate;
use App\Models\UserProfile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final class PublicYouthDirectoryService
{
    public function paginate(mixed $search, mixed $area): LengthAwarePaginator
    {
        $query = $this->publicQuery();
        $term = $this->normalize($search);
        $areaName = $this->normalize($area);

        $this->applySearch($query, $term);
        $this->applyArea($query, $areaName);

        return $query->orderBy('full_name')->orderBy('id')->paginate(12)->withQueryString()
            ->through(fn (UserProfile $profile): array => $this->card($profile));
    }

    public function search(mixed $search, int $limit = 8): Collection
    {
        $term = $this->normalize($search);
        if ($term === '') {
            return collect();
        }

        $query = $this->publicQuery();
        $this->applySearch($query, $term);

        return $query->orderBy('full_name')->limit($limit)->get()->map(fn (UserProfile $profile): array => $this->card($profile));
    }

    public function areas(): Collection
    {
        return UserProfile::query()->whereHas('user', fn (Builder $user) => $user
            ->whereHas('roles', fn (Builder $roles) => $roles->where('name', 'youth'))
            ->whereHas('profileVisibility', fn (Builder $visibility) => $visibility->where('is_profile_public', true))
            ->whereHas('primaryDomicile.administrativeArea'))
            ->with('user.primaryDomicile.administrativeArea')
            ->get()->pluck('user.primaryDomicile.administrativeArea.name')->filter()->unique()->sort()->values();
    }

    private function publicQuery(): Builder
    {
        return UserProfile::query()
            ->select(['id', 'user_id', 'public_slug', 'full_name', 'bio', 'occupation_title', 'profile_photo_path'])
            ->whereHas('user', fn (Builder $user) => $user
                ->whereHas('roles', fn (Builder $roles) => $roles->where('name', 'youth'))
                ->whereHas('profileVisibility', fn (Builder $visibility) => $visibility->where('is_profile_public', true)))
            ->with([
                'user' => fn ($user) => $user->withCount([
                    'activityParticipations as completed_activities_count' => fn (Builder $participations) => $participations
                        ->where('registration_status', ActivityParticipation::REGISTRATION_ACCEPTED)
                        ->where('completion_status', ActivityParticipation::COMPLETION_COMPLETED),
                    'organizationMemberships as active_communities_count' => fn (Builder $memberships) => $memberships
                        ->where('membership_status', OrganizationMembership::STATUS_ACTIVE)
                        ->whereHas('organization', fn (Builder $organizations) => $organizations
                            ->where('review_status', Organization::REVIEW_APPROVED)
                            ->where('operational_status', Organization::OPERATIONAL_ACTIVE)),
                    'certificates as verified_certificates_count' => fn (Builder $certificates) => $certificates
                        ->where('source_type', UserCertificate::SOURCE_SIPORA)
                        ->where('verification_status', UserCertificate::STATUS_VERIFIED),
                ]),
                'user.profileVisibility',
                'user.primaryDomicile.administrativeArea',
                'user.skills' => fn ($skills) => $skills->select(['skills.id', 'skills.name'])->orderBy('name')->limit(4),
            ]);
    }

    private function applySearch(Builder $query, string $term): void
    {
        if ($term === '') {
            return;
        }

        $like = '%'.addcslashes($term, '%_\\').'%';
        $query->where(function (Builder $search) use ($like): void {
            $search->where('full_name', 'like', $like)
                ->orWhere(fn (Builder $bio) => $bio->where('bio', 'like', $like)
                    ->whereHas('user.profileVisibility', fn (Builder $visibility) => $visibility->where('show_bio', true)))
                ->orWhereHas('user', fn (Builder $user) => $user
                    ->whereHas('profileVisibility', fn (Builder $visibility) => $visibility->where('show_skills', true))
                    ->whereHas('skills', fn (Builder $skills) => $skills->where('skills.name', 'like', $like)));
        });
    }

    private function applyArea(Builder $query, string $area): void
    {
        if ($area !== '') {
            $query->whereHas('user.primaryDomicile.administrativeArea', fn (Builder $areas) => $areas->where('name', $area));
        }
    }

    private function card(UserProfile $profile): array
    {
        $visibility = $profile->user->profileVisibility;

        return [
            'name' => $profile->full_name,
            'slug' => $profile->public_slug,
            'url' => route('portfolio.show', $profile->public_slug),
            'photo_url' => $visibility->show_photo && $profile->profile_photo_path ? route('portfolio.photo', $profile->public_slug) : null,
            'headline' => $profile->occupation_title,
            'bio' => $visibility->show_bio ? $profile->bio : null,
            'domicile' => $profile->user->primaryDomicile?->administrativeArea?->name,
            'skills' => $visibility->show_skills ? $profile->user->skills->pluck('name')->all() : [],
            'activities_count' => $visibility->show_activity_passport ? $profile->user->completed_activities_count : null,
            'communities_count' => $visibility->show_community_membership ? $profile->user->active_communities_count : null,
            'certificates_count' => $visibility->show_certificates ? $profile->user->verified_certificates_count : null,
        ];
    }

    private function normalize(mixed $value): string
    {
        return mb_substr(trim(is_string($value) ? $value : ''), 0, 120);
    }
}
