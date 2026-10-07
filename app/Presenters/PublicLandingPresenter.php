<?php

namespace App\Presenters;

use App\Models\Interest;
use App\Services\Discovery\ActivityDiscoveryService;
use App\Services\Discovery\CommunityDiscoveryService;
use App\Services\Discovery\OpportunityDiscoveryService;
use App\Services\Discovery\ProgramDiscoveryService;
use App\Services\YouthStatisticsService;

/**
 * Mixed public landing read model. Approved domains and statistics come from
 * database queries; only the generic Portfolio feature illustration is static.
 */
final class PublicLandingPresenter
{
    public function __construct(
        private readonly ActivityDiscoveryService $activities,
        private readonly CommunityDiscoveryService $communities,
        private readonly OpportunityDiscoveryService $opportunities,
        private readonly ProgramDiscoveryService $programs,
        private readonly YouthStatisticsService $statistics,
    ) {}

    /** @return array<string, mixed> */
    public function present(): array
    {
        $interestIcons = ['💻', '🎨', '🏃', '🌱', '📚', '🤝', '💼', '🎭'];
        $databaseInterests = Interest::query()->orderBy('name')->limit(8)->get();
        $interests = $databaseInterests->isNotEmpty()
            ? $databaseInterests->values()->map(fn (Interest $interest, int $index): array => [
                'icon' => $interestIcons[$index % count($interestIcons)],
                'name' => $interest->name,
            ])->all()
            : [
                ['icon' => '💻', 'name' => 'Teknologi'], ['icon' => '🎨', 'name' => 'Seni & Kreatif'],
                ['icon' => '🏃', 'name' => 'Olahraga'], ['icon' => '🌱', 'name' => 'Lingkungan'],
                ['icon' => '📚', 'name' => 'Pendidikan'], ['icon' => '🤝', 'name' => 'Sosial'],
                ['icon' => '💼', 'name' => 'Kewirausahaan'], ['icon' => '🎭', 'name' => 'Budaya'],
            ];

        return [
            'presentation' => [
                'is_placeholder' => true,
                'source' => 'mixed_landing_presentation',
                'database_domains' => ['interests', 'activities', 'communities', 'opportunities', 'programs', 'statistics'],
                'placeholder_domains' => ['portfolio_showcase'],
                'interests_source' => $databaseInterests->isNotEmpty() ? 'database' : 'placeholder_fallback',
            ],
            'interests' => $interests,
            'activities' => $this->activities->featured(),
            'communities' => $this->communities->featured(),
            'opportunities' => $this->opportunities->featured(),
            'programs' => $this->programs->featured(),
            'youthStatistics' => $this->statistics->summary(),
        ];
    }
}
