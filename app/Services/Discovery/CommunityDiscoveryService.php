<?php

namespace App\Services\Discovery;

use App\Models\AdministrativeArea;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationMembership;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final class CommunityDiscoveryService
{
    /** @return array{communities: LengthAwarePaginator, categories: Collection, locations: Collection, filters: array<string, string>} */
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

        return [
            'communities' => $query->orderByDesc('approved_at')->orderBy('name')->paginate(12)->withQueryString(),
            'categories' => OrganizationCategory::query()->orderBy('name')->get(['id', 'name', 'slug']),
            'locations' => AdministrativeArea::query()->where('area_level', 'district')->orderBy('name')->get(['id', 'code', 'name']),
            'filters' => $filters,
        ];
    }

    public function publicQuery(): Builder
    {
        return Organization::query()
            ->where('review_status', Organization::REVIEW_APPROVED)
            ->where('operational_status', Organization::OPERATIONAL_ACTIVE)
            ->with(['category:id,name,slug', 'administrativeArea:id,name,code'])
            ->withCount([
                'memberships as active_members_count' => fn (Builder $query): Builder => $query->where('membership_status', OrganizationMembership::STATUS_ACTIVE),
                'activities as public_activities_count' => fn (Builder $query): Builder => $query
                    ->where('review_status', 'approved')->where('publication_status', 'published'),
            ]);
    }

    public function featured(int $limit = 3): Collection
    {
        return $this->publicQuery()->orderByDesc('approved_at')->orderBy('name')->limit($limit)->get();
    }

    public function applySearch(Builder $query, string $term): void
    {
        if ($term === '') {
            return;
        }

        $pattern = '%'.addcslashes($term, '\\%_').'%';
        $query->where(function (Builder $search) use ($pattern): void {
            $search->where('name', 'like', $pattern)
                ->orWhere('description', 'like', $pattern)
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
        ];
    }
}
