<?php

namespace App\Services\Discovery;

use App\Services\Youth\PublicYouthDirectoryService;
use Illuminate\Support\Collection;

final class GlobalSearchService
{
    public function __construct(
        private readonly ActivityDiscoveryService $activities,
        private readonly CommunityDiscoveryService $communities,
        private readonly OpportunityDiscoveryService $opportunities,
        private readonly ProgramDiscoveryService $programs,
        private readonly PublicYouthDirectoryService $youth,
    ) {}

    /** @return array{query: string, activities: Collection, communities: Collection, opportunities: Collection, programs: Collection} */
    public function search(mixed $input): array
    {
        $query = mb_substr(trim(is_string($input) ? $input : ''), 0, 120);

        if ($query === '') {
            return ['query' => '', 'activities' => collect(), 'communities' => collect(), 'opportunities' => collect(), 'programs' => collect(), 'youth' => collect()];
        }

        $activities = $this->activities->publicQuery();
        $this->activities->applySearch($activities, $query);

        $communities = $this->communities->publicQuery();
        $this->communities->applySearch($communities, $query);

        $opportunities = $this->opportunities->publicQuery();
        $this->opportunities->applySearch($opportunities, $query);

        $programs = $this->programs->publicQuery();
        $this->programs->applySearch($programs, $query);

        return [
            'query' => $query,
            'activities' => $activities->orderBy('start_at')->limit(8)->get(),
            'communities' => $communities->orderBy('name')->limit(8)->get(),
            'opportunities' => $opportunities->orderByRaw('deadline_at IS NULL')->orderBy('deadline_at')->limit(8)->get(),
            'programs' => $programs->orderByRaw("CASE execution_status WHEN 'running' THEN 0 ELSE 1 END")->orderByDesc('start_date')->limit(8)->get(),
            'youth' => $this->youth->search($query),
        ];
    }
}
