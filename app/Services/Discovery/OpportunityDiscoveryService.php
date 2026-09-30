<?php

namespace App\Services\Discovery;

use App\Models\AdministrativeArea;
use App\Models\Opportunity;
use App\Models\OpportunityCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final class OpportunityDiscoveryService
{
    public function paginate(array $input): array
    {
        $filters = $this->filters($input);
        $query = $this->publicQuery();
        $this->applySearch($query, $filters['q']);

        if ($filters['category'] !== '') {
            $query->whereHas('category', fn (Builder $category): Builder => $category->where('slug', $filters['category']));
        }
        if ($filters['location'] !== '') {
            $query->whereHas('administrativeArea', fn (Builder $area): Builder => $area->where('code', $filters['location']));
        }
        if ($filters['deadline'] === 'open') {
            $query->where(fn (Builder $deadline): Builder => $deadline->whereNull('deadline_at')->orWhere('deadline_at', '>=', now()));
        } elseif ($filters['deadline'] === 'expired') {
            $query->whereNotNull('deadline_at')->where('deadline_at', '<', now());
        }

        $query->orderByRaw('deadline_at IS NULL')->orderBy('deadline_at')->orderByDesc('published_at')->orderBy('title');

        return [
            'opportunities' => $query->paginate(12)->withQueryString(),
            'categories' => OpportunityCategory::query()->orderBy('name')->get(['id', 'name', 'slug']),
            'locations' => AdministrativeArea::query()->where('area_level', 'district')->orderBy('name')->get(['id', 'code', 'name']),
            'filters' => $filters,
        ];
    }

    public function publicQuery(): Builder
    {
        return Opportunity::query()
            ->where('publication_status', Opportunity::STATUS_PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with(['category:id,name,slug', 'organization:id,name,slug', 'administrativeArea:id,name,code']);
    }

    public function featured(int $limit = 3): Collection
    {
        return $this->publicQuery()->where(fn (Builder $deadline): Builder => $deadline->whereNull('deadline_at')->orWhere('deadline_at', '>=', now()))
            ->orderByRaw('deadline_at IS NULL')->orderBy('deadline_at')->orderByDesc('published_at')->limit($limit)->get();
    }

    public function applySearch(Builder $query, string $term): void
    {
        if ($term === '') {
            return;
        }
        $pattern = '%'.addcslashes($term, '\\%_').'%';
        $query->where(function (Builder $search) use ($pattern): void {
            $search->where('title', 'like', $pattern)
                ->orWhere('provider_name', 'like', $pattern)
                ->orWhere('description', 'like', $pattern)
                ->orWhere('location_text', 'like', $pattern)
                ->orWhereHas('category', fn (Builder $category): Builder => $category->where('name', 'like', $pattern))
                ->orWhereHas('administrativeArea', fn (Builder $area): Builder => $area->where('name', 'like', $pattern));
        });
    }

    private function filters(array $input): array
    {
        return [
            'q' => mb_substr(trim((string) ($input['q'] ?? '')), 0, 120),
            'category' => mb_substr(trim((string) ($input['category'] ?? '')), 0, 190),
            'location' => mb_substr(trim((string) ($input['location'] ?? '')), 0, 32),
            'deadline' => in_array($input['deadline'] ?? '', ['open', 'expired', 'all'], true) ? $input['deadline'] : 'open',
        ];
    }
}
