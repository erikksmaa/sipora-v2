<?php

namespace App\Services\Youth;

use App\Models\User;

final class ProfileCompletionService
{
    /**
     * Profile completion weighting:
     * - Basic Profile (70%): full_name (10%), birth_date (10%), gender (5%), phone (5%), occupation_status (10%), bio (10%), domicile (10%), interests (10%)
     * - Enrichment (30%): skills (10%), education (10%), experience_or_achievement (10%)
     *
     * @var array<string, int>
     */
    public const WEIGHTS = [
        'full_name' => 10,
        'birth_date' => 10,
        'gender' => 5,
        'phone' => 5,
        'occupation_status' => 10,
        'bio' => 10,
        'domicile' => 10,
        'interests' => 10,
        'skills' => 10,
        'education' => 10,
        'experience_or_achievement' => 10,
    ];

    /** @return array{percentage:int, completed:list<string>, missing:list<string>} */
    public function calculate(User $user): array
    {
        $user->loadMissing([
            'profile',
            'primaryDomicile',
            'interests',
            'skills',
            'educations',
            'organizationExperiences',
            'achievements',
        ]);
        $profile = $user->profile;

        $present = [
            'full_name' => filled($profile?->full_name),
            'birth_date' => filled($profile?->birth_date),
            'gender' => filled($profile?->gender),
            'phone' => filled($profile?->phone),
            'occupation_status' => filled($profile?->occupation_status),
            'bio' => filled($profile?->bio),
            'domicile' => $user->primaryDomicile !== null,
            'interests' => $user->interests->isNotEmpty(),
            'skills' => $user->skills->isNotEmpty(),
            'education' => $user->educations->isNotEmpty(),
            'experience_or_achievement' => $user->organizationExperiences->isNotEmpty() || $user->achievements->isNotEmpty(),
        ];

        return [
            'percentage' => collect(self::WEIGHTS)->sum(fn (int $weight, string $field): int => $present[$field] ? $weight : 0),
            'completed' => array_keys(array_filter($present)),
            'missing' => array_keys(array_filter($present, fn (bool $value): bool => ! $value)),
        ];
    }

    public function nextStep(User $user): string
    {
        $user->loadMissing([
            'profile',
            'primaryDomicile',
            'interests',
            'skills',
            'educations',
        ]);

        return match (true) {
            $user->profile === null => 'profile',
            $user->primaryDomicile === null => 'domicile',
            $user->interests->isEmpty() => 'interests',
            $user->skills->isEmpty() => 'skills',
            default => 'complete',
        };
    }
}
