<?php

namespace App\Services\Discovery;

use Illuminate\Support\Collection;

final class GlobalSearchService
{
    public function __construct(
        private readonly ActivityDiscoveryService $activities,
        private readonly CommunityDiscoveryService $communities,
    ) {}

    /** @return array{query: string, activities: Collection, communities: Collection} */
    public function search(mixed $input): array
    {
        $query = mb_substr(trim(is_string($input) ? $input : ''), 0, 120);

        if ($query === '') {
            return ['query' => '', 'activities' => collect(), 'communities' => collect()];
        }

        $activities = $this->activities->publicQuery();
        $this->activities->applySearch($activities, $query);

        $communities = $this->communities->publicQuery();
        $this->communities->applySearch($communities, $query);

        return [
            'query' => $query,
            'activities' => $activities->orderBy('start_at')->limit(8)->get(),
            'communities' => $communities->orderBy('name')->limit(8)->get(),
        ];
    }
}
