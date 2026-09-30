<?php

namespace App\Services\Discovery;

use App\Models\Organization;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\ProgramProposal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final class ProgramDiscoveryService
{
    public function paginate(array $input): array
    {
        $filters = $this->filters($input);
        $query = $this->publicQuery()->withCount([
            'activities as public_activities_count' => fn (Builder $activity): Builder => $activity
                ->where('review_status', 'approved')->where('publication_status', 'published'),
        ]);
        $this->applySearch($query, $filters['q']);

        if ($filters['category'] !== '') {
            $query->whereHas('category', fn (Builder $category): Builder => $category->where('slug', $filters['category']));
        }
        if ($filters['status'] !== '') {
            $query->where('execution_status', $filters['status']);
        }

        return [
            'programs' => $this->orderPublic($query)->paginate(12)->withQueryString(),
            'categories' => ProgramCategory::query()->orderBy('name')->get(['id', 'name', 'slug']),
            'filters' => $filters,
        ];
    }

    public function publicQuery(): Builder
    {
        return Program::query()
            ->whereIn('execution_status', [Program::STATUS_RUNNING, Program::STATUS_COMPLETED])
            ->whereHas('latestProposal', fn (Builder $proposal): Builder => $proposal->where('status', ProgramProposal::STATUS_APPROVED))
            ->whereHas('organization', fn (Builder $organization): Builder => $organization
                ->where('review_status', Organization::REVIEW_APPROVED)
                ->where('operational_status', Organization::OPERATIONAL_ACTIVE))
            ->with(['category:id,name,slug', 'organization:id,name,slug']);
    }

    public function featured(int $limit = 3): Collection
    {
        return $this->orderPublic($this->publicQuery())->limit($limit)->get();
    }

    public function isPublic(Program $program): bool
    {
        return $this->publicQuery()->whereKey($program->getKey())->exists();
    }

    public function applySearch(Builder $query, string $term): void
    {
        if ($term === '') {
            return;
        }

        $pattern = '%'.addcslashes($term, '\\%_').'%';
        $query->where(function (Builder $search) use ($pattern): void {
            $search->where('title', 'like', $pattern)
                ->orWhere('description', 'like', $pattern)
                ->orWhere('objectives', 'like', $pattern)
                ->orWhereHas('category', fn (Builder $category): Builder => $category->where('name', 'like', $pattern))
                ->orWhereHas('organization', fn (Builder $organization): Builder => $organization->where('name', 'like', $pattern));
        });
    }

    private function orderPublic(Builder $query): Builder
    {
        return $query->orderByRaw("CASE execution_status WHEN 'running' THEN 0 ELSE 1 END")
            ->orderByDesc('start_date')->orderBy('title');
    }

    private function filters(array $input): array
    {
        return [
            'q' => mb_substr(trim((string) ($input['q'] ?? '')), 0, 120),
            'category' => mb_substr(trim((string) ($input['category'] ?? '')), 0, 190),
            'status' => in_array($input['status'] ?? '', [Program::STATUS_RUNNING, Program::STATUS_COMPLETED], true) ? $input['status'] : '',
        ];
    }
}
