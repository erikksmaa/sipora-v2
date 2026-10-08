<?php

namespace App\Services;

use App\Models\SocialFollow;
use App\Models\SocialPost;
use App\Models\User;
use App\Services\Discovery\ActivityDiscoveryService;
use App\Services\Discovery\CommunityDiscoveryService;
use App\Services\Discovery\OpportunityDiscoveryService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class SocialFeedService
{
    /** Derived discovery events are never persisted: current public visibility is authoritative. */
    public function paginate(string $tab, ?User $user): LengthAwarePaginator
    {
        $following = $tab === 'following';
        $posts = ($tab === 'mine' && $user ? SocialPost::where('author_id', $user->getKey())->whereNull('organization_id')->where('is_removed', false)->where('is_hidden', false) : SocialPost::published())->when($tab === 'community', fn ($q) => $q->whereNotNull('organization_id'));
        if ($following) {
            $posts->where(function ($q) use ($user) {
                $this->followed($q, 'social_posts.organization_id', 'organization_id', $user);
                $q->orWhere(function ($youth) use ($user) {
                    $youth->whereNull('organization_id')->whereHas('author.profileVisibility', fn ($v) => $v->where('is_profile_public', true));
                    $this->followed($youth, 'social_posts.author_id', 'youth_id', $user);
                });
            });
        }
        $visiblePosts = clone $posts;
        $queries = [];
        if (! in_array($tab, ['activity', 'opportunity'], true)) {
            $queries[] = $posts->select([])->selectRaw("id, 'post' as kind, created_at as occurred_at")->toBase();
        }
        if (in_array($tab, ['all', 'following', 'activity'], true)) {
            $q = app(ActivityDiscoveryService::class)->publicQuery()->whereNotNull('published_at')->where('published_at', '<=', now());
            if ($following) {
                $this->followed($q, 'activities.organization_id', 'organization_id', $user);
            }
            $queries[] = $q->select([])->selectRaw("id, 'activity' as kind, published_at as occurred_at")->toBase();
        }
        if (in_array($tab, ['all', 'following', 'community'], true)) {
            $q = app(CommunityDiscoveryService::class)->publicQuery()->whereNotNull('approved_at')->where('approved_at', '<=', now());
            if ($following) {
                $this->followed($q, 'organizations.id', 'organization_id', $user);
            }
            $queries[] = $q->select([])->selectRaw("id, 'community' as kind, approved_at as occurred_at")->toBase();
        }
        if (in_array($tab, ['all', 'following', 'opportunity'], true)) {
            $q = app(OpportunityDiscoveryService::class)->publicQuery()->where(fn ($d) => $d->whereNull('deadline_at')->orWhere('deadline_at', '>=', now()));
            if ($following) {
                $this->followed($q, 'opportunities.organization_id', 'organization_id', $user);
            }
            $queries[] = $q->select([])->selectRaw("id, 'opportunity' as kind, published_at as occurred_at")->toBase();
        }
        $union = array_shift($queries);
        foreach ($queries as $query) {
            $union->unionAll($query);
        }
        $page = DB::query()->fromSub($union, 'timeline')->orderByDesc('occurred_at')->orderBy('kind')->orderByDesc('id')->paginate(12)->withQueryString();
        $ids = collect($page->items())->groupBy('kind')->map(fn ($rows) => $rows->pluck('id')->all());
        $models = [];
        foreach ($ids as $kind => $keys) {
            $q = match ($kind) {
                'post' => (clone $visiblePosts)->select('social_posts.*')->with(['author.profile', 'author.profileVisibility', 'organization'])->withCount(['likes', 'comments' => fn ($c) => $c->where('is_hidden', false)])
                    ->withExists(['likes as liked' => fn ($l) => $l->where('user_id', $user?->getKey() ?? '')]),
                'activity' => app(ActivityDiscoveryService::class)->publicQuery(),
                'community' => app(CommunityDiscoveryService::class)->publicQuery(),
                'opportunity' => app(OpportunityDiscoveryService::class)->publicQuery(),
            };
            foreach ($q->whereKey($keys)->get() as $model) {
                $models[$kind][bin2hex($model->getKey())] = $model;
            }
        }
        $page->setCollection(collect($page->items())->map(function ($row) use ($models) {
            return ['kind' => $row->kind, 'model' => $models[$row->kind][bin2hex($row->id)] ?? null];
        })->filter(fn ($row) => $row['model'] !== null)->values());
        $targets = $page->getCollection()->map(fn ($e) => $e['kind'] === 'post' ? ($e['model']->organization_id ?? $e['model']->author_id) : ($e['kind'] === 'community' ? $e['model']->getKey() : $e['model']->organization_id))->filter()->all();
        $follows = $user && $targets ? SocialFollow::where('user_id', $user->getKey())->where(fn ($q) => $q->whereIn('youth_id', $targets)->orWhereIn('organization_id', $targets))->get() : collect();
        $page->setCollection($page->getCollection()->map(function ($entry) use ($follows) {
            $target = $entry['kind'] === 'post' ? ($entry['model']->organization_id ?? $entry['model']->author_id) : ($entry['kind'] === 'community' ? $entry['model']->getKey() : $entry['model']->organization_id);
            $entry['followed'] = $follows->contains(fn ($f) => ($f->organization_id ?? $f->youth_id) === $target);

            return $entry;
        }));

        return $page;
    }

    private function followed(Builder $query, string $column, string $target, ?User $user): void
    {
        $query->whereExists(fn ($f) => $f->selectRaw('1')->from('social_follows')->whereColumn('social_follows.'.$target, $column)->where('social_follows.user_id', $user?->getKey() ?? ''));
    }
}
