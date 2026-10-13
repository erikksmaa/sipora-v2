<?php

namespace App\Support\Demo;

use App\Models\{Activity, Organization, OrganizationMembership, Program, User};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

/** Internal fixture manifest: exact business keys, no schema additions. */
final class DemoAssetManifest
{
    private static ?array $manifest = null;

    public static function enabled(): bool
    {
        return app()->environment('local') && is_file(database_path('seeders/data/demo-assets-manifest.json'));
    }

    public static function data(): array
    {
        return self::$manifest ??= json_decode(file_get_contents(database_path('seeders/data/demo-assets-manifest.json')), true, flags: JSON_THROW_ON_ERROR);
    }

    public static function preflight(): array
    {
        $groups = [];
        foreach (self::data()['activities'] as $id => $activity) {
            $owner = $activity['community_id']; $program = $activity['program_id'];
            if (! isset(self::data()['communities'][$owner], self::data()['programs'][$program])) {
                throw new RuntimeException('Unknown owner/program for '.$id);
            }
            if (isset($groups[$program]) && $groups[$program] !== $owner) {
                throw new RuntimeException('Multiple Community owners for '.$program);
            }
            if ((self::data()['programs'][$program]['community_id'] ?? $owner) !== $owner) {
                throw new RuntimeException('Program owner mismatch for '.$id);
            }
            $groups[$program] = $owner;
        }
        $seen = [];
        $walk = function (mixed $value) use (&$walk, &$seen): void {
            if (! is_array($value)) return;
            if (isset($value['status']) && in_array($value['status'], ['available', 'planned', 'optional'], true)) {
                foreach (array_merge(isset($value['path']) ? [$value['path']] : [], $value['assets'] ?? []) as $path) {
                    if (! is_string($path)) continue;
                    if (str_contains($path, '..') || ! str_starts_with($path, 'public/media/demo/')) throw new RuntimeException('Invalid demo path');
                    $seen[$value['status'].'|'.$path] = $value['status'];
                    if ($value['status'] === 'available' && ! file_exists(base_path($path))) throw new RuntimeException('Missing AVAILABLE asset: '.$path);
                }
            }
            foreach ($value as $child) $walk($child);
        };
        $walk(self::data());
        return ['statuses' => array_count_values($seen), 'ownership_conflicts' => 0];
    }

    public static function source(?array $asset): ?string
    {
        if (! $asset || ($asset['status'] ?? null) !== 'available' || ! isset($asset['path'])) return null;
        $path = base_path($asset['path']);
        if (! is_file($path)) throw new RuntimeException('Available file missing: '.$asset['path']);
        return $path;
    }

    public static function store(?array $asset, string $disk): ?string
    {
        $source = self::source($asset);
        if (! $source) return null;
        $relative = 'demo-manifest/'.hash_file('sha256', $source).'.'.strtolower(pathinfo($source, PATHINFO_EXTENSION));
        if (! Storage::disk($disk)->exists($relative)) Storage::disk($disk)->put($relative, file_get_contents($source));
        return $relative;
    }

    public static function youth(string $id): User
    {
        return User::where('email', $id === 'Y001' ? 'youth1@sipora.test' : 'asset.'.strtolower($id).'@sipora.test')->first()
            ?? User::where('name', self::data()['youth'][$id]['name'])->where('email', 'like', '%@sipora.test')->firstOrFail();
    }

    public static function community(string $id): Organization
    {
        $entry = self::data()['communities'][$id];
        $slug = $id === 'C008' ? 'pemuda-olahraga-pemalang' : Str::slug($entry['name']);
        return Organization::where('slug', $slug)->firstOrFail();
    }

    public static function program(string $id): Program
    {
        return Program::where('slug', Str::slug(self::data()['programs'][$id]['name']))->firstOrFail();
    }

    public static function activity(string $id): Activity
    {
        $title = self::data()['activities'][$id]['title'];
        $slug = Str::slug($title);
        $slug = $id === 'A001' ? $slug : (in_array($id, ['A002','A003','A004','A005'], true) ? 'passport-'.$slug : 'demo-'.$slug);
        return Activity::where('slug', $slug)->firstOrFail();
    }

    public static function ensureCommunities(): void
    {
        foreach (self::data()['communities'] as $id => $entry) {
            $categories = ['C001'=>'teknologi','C002'=>'kreatif','C003'=>'sosial','C004'=>'pendidikan','C005'=>'kewirausahaan',
                'C006'=>'kreatif','C007'=>'sosial','C008'=>'olahraga','C009'=>'pendidikan','C010'=>'kreatif',
                'C011'=>'kreatif','C012'=>'pendidikan','C013'=>'teknologi','C014'=>'kreatif','C015'=>'sosial'];
            $category = \App\Models\OrganizationCategory::where('slug', 'komunitas-'.$categories[$id])->firstOrFail();
            $slug = $id === 'C008' ? 'pemuda-olahraga-pemalang' : Str::slug($entry['name']);
            $leader = self::youth('Y'.substr($id, 1));
            $template = Organization::where('slug', 'komunitas-pemuda-digital-pemalang')->firstOrFail();
            $org = Organization::firstOrCreate(['slug' => $slug], [
                'name' => $entry['name'], 'created_by_user_id' => $leader->getKey(), 'category_id' => $category->getKey(),
                'administrative_area_id' => $leader->primaryDomicile?->administrative_area_id ?? $template->administrative_area_id,
                'description' => 'Komunitas fiktif '.$entry['name'].' untuk DATA DEMO SIPORA.', 'social_links' => (object) [],
                'review_status' => 'approved', 'operational_status' => 'active',
                'approved_at' => now(), 'approved_by' => $template->approved_by,
            ]);
            $org->forceFill(['name' => $entry['name'], 'category_id' => $category->getKey(), 'logo_path' => self::store($entry['logo'] ?? null, 'community_media')])->save();
            // Existing creator remains authoritative; contextual roles are never global roles.
            OrganizationMembership::firstOrCreate(['organization_id' => $org->getKey(), 'user_id' => $org->created_by_user_id],
                ['access_role' => 'leader', 'membership_status' => 'active', 'requested_at' => now(), 'approved_at' => now(), 'approved_by' => $org->created_by_user_id]);
        }
    }

    public static function ensurePrograms(): void
    {
        foreach (self::data()['programs'] as $id => $entry) {
            $existing = Program::where('slug', Str::slug($entry['name']))->first();
            $org = isset($entry['community_id']) ? self::community($entry['community_id'])
                : ($existing?->organization ?? self::community('C001'));
            $template = Program::where('slug', 'pemalang-youth-digital-empowerment-2026')->firstOrFail();
            $program = Program::firstOrCreate(['slug' => Str::slug($entry['name'])], [
                'title' => $entry['name'], 'organization_id' => $org->getKey(), 'created_by_user_id' => $org->created_by_user_id,
                'category_id' => $template->category_id, 'description' => 'Program fiktif untuk DATA DEMO SIPORA.',
                'objectives' => 'Pengembangan kapasitas pemuda.', 'start_date' => $template->start_date, 'end_date' => $template->end_date,
                // Completed requires a real evaluation. Rejected is a proposal status, never Program execution status.
                'execution_status' => 'planned',
            ]);
            if ($program->organization_id !== $org->getKey()) {
                $program->forceFill(['organization_id' => $org->getKey(), 'created_by_user_id' => $org->created_by_user_id])->save();
            }
        }
    }

    public static function activityEntry(string $title): ?array
    {
        foreach (self::data()['activities'] as $id => $entry) if ($entry['title'] === $title) return ['id' => $id] + $entry;
        return null;
    }

    public static function normalizeExecutionState(): void
    {
        foreach (array_keys(self::data()['programs']) as $id) {
            $program = self::program($id);
            if ($program->execution_status === 'running' && ! $program->proposals()->where('status', 'approved')->exists()) {
                // A scenario label is not approval evidence. New/missing proposals
                // keep the Program planned until the native workflow approves it.
                $program->forceFill(['execution_status' => 'planned'])->save();
            }
        }
    }

    public static function publicAsset(string $domain, string $slug, string $slot = 'cover'): ?string
    {
        if (! self::enabled()) return null;
        $domain = $domain === 'organizations' ? 'communities' : $domain;
        foreach (self::data()[$domain] ?? [] as $id => $entry) {
            $title = $entry['title'] ?? $entry['name'] ?? '';
            $base = Str::slug($title);
            $aliases = [$base, 'demo-'.$base, 'passport-'.$base, $id === 'C008' ? 'pemuda-olahraga-pemalang' : $base];
            if ($domain === 'opportunities') $aliases[] = self::opportunitySlug($id);
            if (! in_array($slug, $aliases, true)) continue;
            $source = self::source($entry[$slot] ?? null);
            return $source ? asset(substr($entry[$slot]['path'], strlen('public/'))) : null;
        }
        return null;
    }

    public static function gallery(string $domain, string $slug): array
    {
        if (! self::enabled()) return [];
        foreach (self::data()[$domain] ?? [] as $entry) {
            $base = Str::slug($entry['title'] ?? $entry['name']);
            if (! in_array($slug, [$base, 'demo-'.$base, 'passport-'.$base], true)) continue;
            return array_values(array_map(fn ($a) => asset(substr($a['path'], 7)), array_filter($entry['documentation'] ?? $entry['gallery'] ?? [], fn ($a) => self::source($a) !== null)));
        }
        return [];
    }

    public static function portfolio(User $user): array
    {
        if (! self::enabled()) return [];
        foreach (self::data()['youth'] as $id => $entry) {
            if ($entry['name'] !== $user->name || self::youth($id)->getKey() !== $user->getKey()) continue;
            return array_values(array_map(fn ($a) => ['url' => asset(substr($a['path'], 7)), 'title' => $a['title'], 'note' => $a['note'] ?? ''],
                array_filter($entry['portfolio_assets'] ?? [], fn ($a) => self::source($a) !== null)));
        }
        return [];
    }

    public static function opportunitySlug(string $id): string
    {
        return self::data()['seeder_keys']['opportunities'][$id]['slug'];
    }
}
