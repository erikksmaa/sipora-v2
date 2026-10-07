<?php

namespace App\Services\Youth;

use App\Models\User;
use App\Models\UserIdentity;

final class YouthOnboardingService
{
    /** @return array<string, mixed> */
    public function state(User $user): array
    {
        // Query current records so removing required data immediately relocks later steps.
        $profile = $user->profile()->first();
        $emailVerified = $user->hasVerifiedEmail();
        $biodataComplete = filled($profile?->full_name)
            && $profile?->birth_date !== null
            && $user->primaryDomicile()->exists();
        $identityStatus = $user->identity()->value('verification_status') ?? UserIdentity::STATUS_UNVERIFIED;
        $identityVerified = $identityStatus === UserIdentity::STATUS_VERIFIED;
        $profileComplete = filled($profile?->bio) && filled($profile?->occupation_status);
        $hasSkill = $user->skills()->exists() || $user->customPortfolioTags()->where('kind', 'skill')->exists();
        $hasInterest = $user->interests()->exists() || $user->customPortfolioTags()->where('kind', 'interest')->exists();
        $portfolioBaselineComplete = $hasSkill && $hasInterest;
        $onboardingComplete = $emailVerified && $biodataComplete && $identityVerified
            && $profileComplete && $portfolioBaselineComplete;

        $currentStep = match (true) {
            ! $emailVerified => 'email',
            ! $biodataComplete => 'biodata',
            ! $identityVerified => 'identity',
            ! $profileComplete => 'profile',
            ! $portfolioBaselineComplete => 'portfolio',
            default => 'complete',
        };

        $nextAction = match ($currentStep) {
            'email' => ['route' => 'verification.notice', 'label' => 'Verifikasi email'],
            'biodata' => ['route' => 'youth.biodata.edit', 'label' => 'Lengkapi Biodata'],
            'identity' => ['route' => 'youth.identity-verification', 'label' => $identityStatus === UserIdentity::STATUS_PENDING ? 'Tunggu Verifikasi' : 'Kirim Verifikasi Identitas'],
            'profile' => ['route' => 'youth.profile.edit', 'label' => 'Lengkapi Profil'],
            'portfolio' => ['route' => $hasSkill ? 'youth.interests.edit' : 'youth.skills.edit', 'label' => $hasSkill ? 'Pilih Minat' : 'Tambahkan Keahlian'],
            default => ['route' => 'youth.home', 'label' => 'Buka Ruang Youth'],
        };

        $orderedSteps = [
            'email' => $emailVerified,
            'biodata' => $biodataComplete,
            'identity' => $identityVerified,
            'profile' => $profileComplete,
            'portfolio' => $portfolioBaselineComplete,
            'complete' => $onboardingComplete,
        ];
        $stepKeys = array_keys($orderedSteps);
        $currentIndex = array_search($currentStep, $stepKeys, true);
        $completedSteps = array_slice($stepKeys, 0, $currentIndex);
        if ($currentStep === 'complete') {
            $completedSteps[] = 'complete';
        }
        $lockedSteps = array_slice($stepKeys, $currentIndex + 1);

        return compact(
            'emailVerified', 'biodataComplete', 'identityStatus', 'identityVerified',
            'profileComplete', 'hasSkill', 'hasInterest', 'portfolioBaselineComplete',
            'onboardingComplete', 'currentStep', 'nextAction', 'completedSteps', 'lockedSteps'
        );
    }

    /** @param array<string, mixed> $state */
    public function allows(array $state, string $stage): bool
    {
        return match ($stage) {
            'biodata' => $state['emailVerified'],
            'identity' => $state['emailVerified'] && $state['biodataComplete'],
            'profile' => $state['emailVerified'] && $state['biodataComplete'] && $state['identityVerified'],
            'portfolio' => $state['emailVerified'] && $state['biodataComplete'] && $state['identityVerified'] && $state['profileComplete'],
            'complete' => $state['onboardingComplete'],
            default => false,
        };
    }

    /** @param array<string, mixed> $state */
    public function lockedReason(array $state): string
    {
        return match ($state['currentStep']) {
            'email' => 'Verifikasi email terlebih dahulu.',
            'biodata' => 'Selesaikan Biodata terlebih dahulu.',
            'identity' => $state['identityStatus'] === UserIdentity::STATUS_PENDING
                ? 'Menunggu verifikasi identitas oleh Admin.'
                : 'Selesaikan verifikasi identitas terlebih dahulu.',
            'profile' => 'Lengkapi Profil terlebih dahulu.',
            'portfolio' => 'Tambahkan minimal satu Keahlian dan satu Minat.',
            default => 'Lengkapi akun terlebih dahulu untuk menggunakan fitur ini.',
        };
    }
}
