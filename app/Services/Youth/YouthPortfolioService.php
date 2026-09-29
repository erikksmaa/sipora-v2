<?php

namespace App\Services\Youth;

use App\Models\ActivityParticipation;
use App\Models\Organization;
use App\Models\OrganizationMembership;
use App\Models\User;
use App\Models\UserCertificate;
use App\Models\UserProfile;

final class YouthPortfolioService
{
    public function forOwner(User $user): array
    {
        return $this->compose($user, false);
    }

    public function forPublicSlug(string $slug): ?array
    {
        $profile = UserProfile::query()->where('public_slug', $slug)->with('user.profileVisibility')->first();
        if (! $profile || ! $profile->user->hasRole('youth')
            || ! $profile->user->profileVisibility?->is_profile_public) {
            return null;
        }

        return $this->compose($profile->user, true);
    }

    private function compose(User $user, bool $public): array
    {
        $user->load([
            'profile',
            'profileVisibility',
            'primaryDomicile.administrativeArea',
            'interests' => fn ($query) => $query->orderBy('name'),
            'skills' => fn ($query) => $query->orderBy('name'),
            'educations',
            'organizationExperiences',
            'achievements' => fn ($query) => $query->whereIn('verification_status', ['self_reported', 'pending', 'verified']),
            'organizationMemberships' => fn ($query) => $query
                ->where('membership_status', OrganizationMembership::STATUS_ACTIVE)
                ->whereHas('organization', fn ($organization) => $organization
                    ->where('review_status', Organization::REVIEW_APPROVED)
                    ->where('operational_status', Organization::OPERATIONAL_ACTIVE))
                ->with('organization')
                ->latest('approved_at'),
            'activityParticipations' => fn ($query) => $query
                ->where('registration_status', ActivityParticipation::REGISTRATION_ACCEPTED)
                ->where('completion_status', ActivityParticipation::COMPLETION_COMPLETED)
                ->with([
                    'activity.organization',
                    'activity.category',
                    'certificate' => fn ($certificate) => $certificate
                        ->where('source_type', UserCertificate::SOURCE_SIPORA)
                        ->where('verification_status', UserCertificate::STATUS_VERIFIED),
                ])
                ->latest('completed_at'),
            'certificates' => fn ($query) => $query
                ->where('source_type', UserCertificate::SOURCE_SIPORA)
                ->where('verification_status', UserCertificate::STATUS_VERIFIED)
                ->latest('issued_at'),
        ]);

        $profile = $user->profile;
        $visibility = $user->profileVisibility;
        $allowed = fn (string $field): bool => ! $public || (bool) $visibility?->{$field};

        $communities = $allowed('show_community_membership')
            ? $user->organizationMemberships->map(fn ($membership): array => [
                'name' => $membership->organization->name,
                'role' => $membership->access_role,
                'position' => $membership->position_title,
                'url' => route('communities.show', $membership->organization),
                'since' => $membership->approved_at?->format('Y'),
            ])->values()->all()
            : [];

        $activities = $allowed('show_activity_passport')
            ? $user->activityParticipations->map(fn ($participation): array => [
                'title' => $participation->activity->title,
                'organizer' => $participation->activity->organization->name,
                'category' => $participation->activity->category?->name,
                'role' => $participation->activity_role,
                'period' => $this->period($participation->activity->start_at, $participation->activity->end_at),
                'completed_at' => $participation->completed_at?->translatedFormat('d M Y'),
                'certificate' => $allowed('show_certificates') && $participation->certificate ? [
                    'number' => $participation->certificate->certificate_number,
                    'verify_url' => route('certificates.verify', $participation->certificate->verification_code),
                ] : null,
            ])->values()->all()
            : [];

        $certificates = $allowed('show_certificates')
            ? $user->certificates->map(fn ($certificate): array => [
                'name' => $certificate->name,
                'issuer' => $certificate->issuer_name,
                'number' => $certificate->certificate_number,
                'issued_at' => $certificate->issued_at->translatedFormat('d M Y'),
                'verify_url' => route('certificates.verify', $certificate->verification_code),
                'owner_url' => $public ? null : route('youth.certificates.show', $certificate),
            ])->values()->all()
            : [];

        $achievements = $allowed('show_achievements')
            ? $user->achievements->map(fn ($achievement): array => [
                'title' => $achievement->title,
                'issuer' => $achievement->issuer_name,
                'date' => $achievement->achievement_date?->translatedFormat('M Y'),
                'description' => $achievement->description,
                'provenance' => $achievement->verification_status === 'verified' ? 'verified' : 'self_reported',
            ])->values()->all()
            : [];

        return [
            'public_slug' => $profile?->public_slug,
            'public_url' => $profile ? route('portfolio.show', $profile->public_slug) : null,
            'is_public' => (bool) $visibility?->is_profile_public,
            'is_owner' => ! $public,
            'identity' => [
                'name' => $profile?->full_name ?: $user->name,
                'headline' => $profile?->occupation_title,
                'bio' => $allowed('show_bio') ? $profile?->bio : null,
                'domicile' => $user->primaryDomicile?->administrativeArea?->name,
                'photo_url' => $allowed('show_photo') && $profile?->profile_photo_path
                    ? ($public ? route('portfolio.photo', $profile->public_slug) : route('youth.profile.photo'))
                    : null,
            ],
            'interests' => $allowed('show_interests') ? $user->interests->pluck('name')->all() : [],
            'skills' => $allowed('show_skills') ? $user->skills->pluck('name')->all() : [],
            'educations' => $allowed('show_education') ? $user->educations->map(fn ($education): array => [
                'level' => $education->education_level,
                'institution' => $education->institution_name,
                'field' => $education->field_of_study,
                'period' => $this->period($education->start_date, $education->end_date, $education->is_current),
                'description' => $education->description,
            ])->values()->all() : [],
            'organization_experiences' => $allowed('show_organization_experience')
                ? $user->organizationExperiences->map(fn ($experience): array => [
                    'organization' => $experience->organization_name,
                    'role' => $experience->role_title,
                    'period' => $this->period($experience->start_date, $experience->end_date, $experience->is_current),
                    'description' => $experience->description,
                ])->values()->all() : [],
            'achievements' => $achievements,
            'communities' => $communities,
            'activities' => $activities,
            'certificates' => $certificates,
            'summary' => [
                'activities' => count($activities),
                'communities' => count($communities),
                'certificates' => count($certificates),
                'achievements' => count($achievements),
            ],
            'visibility' => $public ? [] : [
                'photo' => (bool) $visibility?->show_photo,
                'bio' => (bool) $visibility?->show_bio,
                'interests' => (bool) $visibility?->show_interests,
                'skills' => (bool) $visibility?->show_skills,
                'education' => (bool) $visibility?->show_education,
                'organization_experience' => (bool) $visibility?->show_organization_experience,
                'community_membership' => (bool) $visibility?->show_community_membership,
                'activity_passport' => (bool) $visibility?->show_activity_passport,
                'certificates' => (bool) $visibility?->show_certificates,
                'achievements' => (bool) $visibility?->show_achievements,
            ],
        ];
    }

    private function period($start, $end, bool $current = false): string
    {
        $from = $start?->translatedFormat('M Y') ?: 'Tanggal tidak dicantumkan';
        $until = $current ? 'Sekarang' : ($end?->translatedFormat('M Y') ?: null);

        return $until ? $from.' – '.$until : $from;
    }
}
