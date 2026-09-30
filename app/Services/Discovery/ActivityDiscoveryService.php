<?php

namespace App\Services\Discovery;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\AdministrativeArea;
use App\Models\Organization;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final class ActivityDiscoveryService
{
    /** @return array{activities: LengthAwarePaginator, categories: Collection, locations: Collection, filters: array<string, string>} */
    public function paginate(array $input): array
    {
        $filters = $this->filters($input);
        $query = $this->publicQuery()->withCount([
            'participations as accepted_participants_count' => fn (Builder $query): Builder => $query->where('registration_status', 'accepted'),
        ]);

        $this->applySearch($query, $filters['q']);

        if ($filters['category'] !== '') {
            $query->whereHas('category', fn (Builder $category): Builder => $category->where('slug', $filters['category']));
        }

        if ($filters['location'] !== '') {
            $query->whereHas('administrativeArea', fn (Builder $area): Builder => $area->where('code', $filters['location']));
        }

        if ($filters['mode'] !== '') {
            $query->where('location_type', $filters['mode']);
        }

        if ($filters['registration'] !== '') {
            $query->where('registration_mode', $filters['registration']);
        }

        if ($filters['availability'] === 'open') {
            $query->where(fn (Builder $registration): Builder => $registration
                ->whereNull('registration_open_at')->orWhere('registration_open_at', '<=', now()))
                ->where(fn (Builder $registration): Builder => $registration
                    ->whereNull('registration_close_at')->orWhere('registration_close_at', '>=', now()))
                ->where(fn (Builder $capacity): Builder => $capacity
                    ->whereNull('quota')
                    ->orWhereRaw('(SELECT COUNT(*) FROM activity_participations WHERE activity_participations.activity_id = activities.id AND activity_participations.registration_status = ? AND activity_participations.deleted_at IS NULL) < activities.quota', ['accepted']));
        }

        match ($filters['sort']) {
            'newest' => $query->orderByDesc('published_at')->orderBy('start_at'),
            'closing' => $query->orderByRaw('registration_close_at IS NULL')->orderBy('registration_close_at')->orderBy('start_at'),
            default => $query->orderBy('start_at')->orderBy('title'),
        };

        return [
            'activities' => $query->paginate(12)->withQueryString(),
            'categories' => ActivityCategory::query()->orderBy('name')->get(['id', 'name', 'slug']),
            'locations' => AdministrativeArea::query()->where('area_level', 'district')->orderBy('name')->get(['id', 'code', 'name']),
            'filters' => $filters,
        ];
    }

    public function publicQuery(): Builder
    {
        return Activity::query()
            ->where('review_status', Activity::REVIEW_APPROVED)
            ->where('publication_status', Activity::PUBLICATION_PUBLISHED)
            ->whereIn('execution_status', [Activity::EXECUTION_SCHEDULED, Activity::EXECUTION_ONGOING])
            ->where('end_at', '>=', now())
            ->whereHas('organization', fn (Builder $organization): Builder => $organization
                ->where('review_status', Organization::REVIEW_APPROVED)
                ->where('operational_status', Organization::OPERATIONAL_ACTIVE))
            ->with(['category:id,name,slug', 'organization:id,name,slug', 'administrativeArea:id,name,code']);
    }

    public function featured(int $limit = 3): Collection
    {
        return $this->publicQuery()
            ->withCount(['participations as accepted_participants_count' => fn (Builder $query): Builder => $query->where('registration_status', 'accepted')])
            ->orderBy('start_at')
            ->limit($limit)
            ->get();
    }

    public function applySearch(Builder $query, string $term): void
    {
        if ($term === '') {
            return;
        }

        $pattern = $this->likePattern($term);
        $query->where(function (Builder $search) use ($pattern): void {
            $search->where('title', 'like', $pattern)
                ->orWhere('venue_name', 'like', $pattern)
                ->orWhereHas('organization', fn (Builder $organization): Builder => $organization->where('name', 'like', $pattern))
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
            'mode' => in_array($input['mode'] ?? '', ['offline', 'online', 'hybrid'], true) ? $input['mode'] : '',
            'registration' => in_array($input['registration'] ?? '', ['open', 'approval_required'], true) ? $input['registration'] : '',
            'availability' => ($input['availability'] ?? '') === 'open' ? 'open' : '',
            'sort' => in_array($input['sort'] ?? '', ['upcoming', 'newest', 'closing'], true) ? $input['sort'] : 'upcoming',
        ];
    }

    private function likePattern(string $value): string
    {
        return '%'.addcslashes($value, '\\%_').'%';
    }
}
