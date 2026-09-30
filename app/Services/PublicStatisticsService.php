<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Opportunity;
use App\Models\Organization;
use App\Models\Program;
use App\Services\Discovery\ProgramDiscoveryService;

final class PublicStatisticsService
{
    public function __construct(private readonly ProgramDiscoveryService $programs) {}

    /** @return array<int, array{value: int, label: string}> */
    public function summarize(): array
    {
        return [
            ['value' => Organization::query()->where('review_status', Organization::REVIEW_APPROVED)->where('operational_status', Organization::OPERATIONAL_ACTIVE)->count(), 'label' => 'Community aktif'],
            ['value' => Activity::query()->where('review_status', Activity::REVIEW_APPROVED)->where('publication_status', Activity::PUBLICATION_PUBLISHED)->whereHas('organization', fn ($query) => $query->where('review_status', Organization::REVIEW_APPROVED)->where('operational_status', Organization::OPERATIONAL_ACTIVE))->count(), 'label' => 'Activity publik'],
            ['value' => Opportunity::query()->where('publication_status', Opportunity::STATUS_PUBLISHED)->whereNotNull('published_at')->where('published_at', '<=', now())->count(), 'label' => 'Opportunity publik'],
            ['value' => (clone $this->programs->publicQuery())->where('execution_status', Program::STATUS_COMPLETED)->count(), 'label' => 'Program selesai'],
        ];
    }
}
