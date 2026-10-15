<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\AdministrativeArea;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\Program;
use App\Models\User;
use App\Services\Discovery\ActivityDiscoveryService;
use App\Services\Discovery\CommunityDiscoveryService;
use App\Services\Discovery\ProgramDiscoveryService;
use App\Services\Youth\ProfileCompletionService;
use App\Support\ForumDisplayName;
use Illuminate\Database\Eloquent\Builder;

final class GovernmentDirectoryService
{
    public function __construct(
        private readonly CommunityDiscoveryService $communities,
        private readonly ActivityDiscoveryService $activities,
        private readonly ProgramDiscoveryService $programs,
        private readonly ProfileCompletionService $completion,
    ) {}

    public function authorize(User $actor, string $workspace): void
    {
        abort_unless(in_array($workspace, ['admin', 'verifier'], true) && $actor->hasRole($workspace), 403);
    }

    public function listing(User $actor, string $workspace, string $kind, array $filters): array
    {
        $this->authorize($actor, $workspace);
        $query = $this->query($kind, $workspace);
        $search = trim($filters['q'] ?? '');
        if ($kind === 'youth') {
            if ($search !== '') {
                $pattern = '%'.addcslashes($search, '\\%_').'%';
                $query->where(function (Builder $q) use ($pattern) {
                    $q->whereHas('profile', fn ($p) => $p->where('full_name', 'like', $pattern))->orWhere('name', 'like', $pattern);
                });
                if ($workspace !== 'admin') {
                    $query->whereHas('profileVisibility', fn ($v) => $v->where('is_profile_public', true));
                }
            }
            if (filled($filters['district'] ?? null)) {
                $query->whereHas('primaryDomicile.administrativeArea', fn ($a) => $a->where(fn ($area) => $area->where('code', $filters['district'])->orWhereHas('parent', fn ($parent) => $parent->where('code', $filters['district']))));
                if ($workspace !== 'admin') {
                    $query->whereHas('profileVisibility', fn ($v) => $v->where('is_profile_public', true));
                }
            }
            if (filled($filters['profile'] ?? null)) {
                if ($filters['profile'] === 'public') {
                    $query->whereHas('profileVisibility', fn ($v) => $v->where('is_profile_public', true));
                } else {
                    $query->whereDoesntHave('profileVisibility', fn ($v) => $v->where('is_profile_public', true));
                }
            }
            if ($workspace === 'admin' && filled($filters['identity'] ?? null)) {
                $status = $filters['identity'];
                $query->where(function ($q) use ($status) {
                    $q->whereHas('identity', fn ($i) => $i->where('verification_status', $status));
                    if ($status === 'unverified') {
                        $q->orWhereDoesntHave('identity');
                    }
                });
            }
        } else {
            match ($kind) {
                'communities' => $this->communities->applySearch($query, $search),
                'activities' => $this->activities->applySearch($query, $search),
                'programs' => $this->programs->applySearch($query, $search),
            };
            if (filled($filters['status'] ?? null)) {
                $query->where($kind === 'programs' ? 'execution_status' : 'review_status', $filters['status']);
            }
            if ($kind === 'communities') {
                if (filled($filters['category'] ?? null)) {
                    $query->whereHas('category', fn ($c) => $c->where('slug', $filters['category']));
                }
                if (filled($filters['operational'] ?? null)) {
                    $query->where('operational_status', $filters['operational']);
                }
            }
            if ($kind === 'activities' && filled($filters['publication'] ?? null)) {
                $query->where('publication_status', $filters['publication']);
            }
        }
        $records = $query->orderByDesc('created_at')->orderByDesc('id')->paginate(15)->withQueryString()
            ->through(fn ($record) => $this->project($record, $kind, $workspace));

        return ['records' => $records, 'columns' => $this->columns($kind, $workspace),
            'districts' => $kind === 'youth' ? AdministrativeArea::where('area_level', 'district')->orderBy('name')->get(['code', 'name']) : collect(),
            'categories' => $kind === 'communities' ? OrganizationCategory::orderBy('name')->get(['slug', 'name']) : collect()];
    }

    public function detail(User $actor, string $workspace, string $kind, mixed $key): array
    {
        $this->authorize($actor, $workspace);
        $query = $this->query($kind, $workspace);
        $record = $kind === 'youth' ? $query->whereKey($key)->firstOrFail() : $query->where('slug', $key)->firstOrFail();

        return ['record' => $this->project($record, $kind, $workspace), 'columns' => $this->columns($kind, $workspace)];
    }

    private function query(string $kind, string $workspace): Builder
    {
        if ($kind === 'youth') {
            $query = User::role('youth')->select(['id', 'name', 'email_verified_at', 'created_at'])
                ->with(['profile' => fn ($q) => $workspace === 'admin' ? $q : $q->select(['id', 'user_id', 'full_name', 'public_slug', 'profile_photo_path']), 'profileVisibility', 'primaryDomicile' => fn ($q) => $q->select(['id', 'user_id', 'administrative_area_id', 'address_type', 'is_primary']), 'primaryDomicile.administrativeArea:id,name,code,area_level,parent_id', 'primaryDomicile.administrativeArea.parent:id,name,code,area_level', 'interests:id,name', 'skills:id,name'])
                ->withCount([
                    'organizationMemberships as community_count' => fn ($q) => $q->where('membership_status', 'active')->whereHas('organization', fn ($o) => $o->where('review_status', 'approved')->where('operational_status', 'active')),
                    'activityParticipations as completed_count' => fn ($q) => $q->where('registration_status', 'accepted')->where('completion_status', 'completed'),
                ]);
            if ($workspace === 'admin') {
                $query->with(['identity:id,user_id,verification_status', 'educations:id,user_id', 'organizationExperiences:id,user_id', 'achievements:id,user_id']);
            }

            return $query;
        }
        if ($kind === 'communities') {
            return Organization::with(['category:id,name,slug', 'administrativeArea:id,name,code,area_level,parent_id', 'administrativeArea.parent:id,name,code,area_level',
                'activeMemberships' => fn ($q) => $q->where('access_role', 'leader')->with(['user:id,name', 'user.profile:id,user_id,full_name', 'user.profileVisibility'])])
                ->withCount(['activeMemberships', 'activities', 'programs']);
        }
        if ($kind === 'activities') {
            return Activity::with(['organization:id,name,slug', 'category:id,name,slug', 'program:id,title,slug'])
                ->withCount(['participations as accepted_count' => fn ($q) => $q->where('registration_status', 'accepted')]);
        }

        return Program::with(['organization:id,name,slug', 'category:id,name,slug'])->withCount('activities');
    }

    private function project($record, string $kind, string $workspace): array
    {
        $photo = null;
        $publicUrl = null;
        if ($kind === 'youth') {
            $visibility = $record->profileVisibility;
            $public = $visibility?->is_profile_public && filled($record->profile?->public_slug);
            $admin = $workspace === 'admin';
            $name = $admin ? ($record->profile?->full_name ?: $record->name) : ForumDisplayName::for($record);
            $publicUrl = $public ? route('portfolio.show', $record->profile->public_slug) : null;
            $photo = $public && $visibility->show_photo && $record->profile?->profile_photo_path ? route('portfolio.photo', $record->profile->public_slug) : null;
            $cells = [$admin || $public ? $this->district($record->primaryDomicile?->administrativeArea) : 'Privat'];
            if ($admin) {
                $cells[] = $record->email_verified_at ? 'Terverifikasi' : 'Belum terverifikasi';
                $cells[] = $this->label($record->identity?->verification_status ?? 'unverified');
                $cells[] = $this->completion->calculate($record)['percentage'].'%';
            } else {
                $cells[] = $public && $visibility->show_interests ? ($record->interests->pluck('name')->join(', ') ?: '—') : 'Privat';
                $cells[] = $public && $visibility->show_skills ? ($record->skills->pluck('name')->join(', ') ?: '—') : 'Privat';
            }
            $cells[] = $admin || ($public && $visibility->show_community_membership) ? (string) $record->community_count : 'Privat';
            $cells[] = $admin || ($public && $visibility->show_activity_passport) ? (string) $record->completed_count : 'Privat';
            $cells[] = $public ? 'Publik' : 'Privat';
            $cells[] = $record->created_at->timezone(config('sipora.display_timezone'))->format('d M Y');
            $routeKey = $record->uuid();
        } elseif ($kind === 'communities') {
            $name = $record->name;
            $public = $record->review_status === 'approved' && $record->operational_status === 'active';
            $photo = $public && $record->logo_path ? route('communities.logo', $record) : null;
            $publicUrl = $public ? route('communities.show', $record) : null;
            $leader = $record->activeMemberships->first()?->user;
            $cells = [$record->category?->name ?? '—', $this->district($record->administrativeArea), $this->label($record->review_status), $this->label($record->operational_status), $leader ? ForumDisplayName::for($leader) : '—', (string) $record->active_memberships_count, (string) $record->activities_count, (string) $record->programs_count, $record->created_at->format('d M Y')];
            $routeKey = $record->slug;
        } elseif ($kind === 'activities') {
            $name = $record->title;
            $cells = [$record->organization?->name ?? '—', $record->category?->name ?? '—', $record->start_at?->timezone(config('sipora.display_timezone'))->format('d M Y H:i').' WIB', $this->label($record->review_status), $this->label($record->publication_status), $this->label($record->execution_status), (string) $record->accepted_count, $record->program?->title ?? '—'];
            $routeKey = $record->slug;
        } else {
            $name = $record->title;
            $cells = [$record->organization?->name ?? '—', $record->category?->name ?? '—', $this->label($record->execution_status), $record->start_date?->format('d M Y') ?? '—', $record->end_date?->format('d M Y') ?? '—', (string) $record->activities_count];
            $routeKey = $record->slug;
        }

        return ['name' => $name, 'photo' => $photo, 'cells' => $cells, 'url' => route($workspace.'.directory.'.$kind.'.show', $routeKey), 'public_url' => $publicUrl];
    }

    public function columns(string $kind, string $workspace): array
    {
        return match ($kind) {
            'youth' => array_merge(['Kecamatan'], $workspace === 'admin' ? ['Verifikasi email', 'Status identitas', 'Kelengkapan profil'] : ['Minat publik', 'Skill publik'], ['Community aktif', 'Activity selesai', 'Portfolio', 'Terdaftar']),
            'communities' => ['Kategori', 'Kecamatan', 'Verifikasi', 'Operasional', 'Leader', 'Anggota aktif', 'Activity', 'Program', 'Terdaftar'],
            'activities' => ['Community', 'Kategori', 'Jadwal mulai', 'Verifikasi', 'Publikasi', 'Pelaksanaan', 'Peserta diterima', 'Program'],
            'programs' => ['Community', 'Kategori', 'Pelaksanaan', 'Mulai', 'Selesai', 'Activity'],
        };
    }

    private function district(?AdministrativeArea $area): string
    {
        if ($area?->area_level === 'district') {
            return $area->name;
        }

        return $area?->parent?->area_level === 'district' ? $area->parent->name : '—';
    }

    private function label(string $value): string
    {
        return ['approved' => 'Disetujui', 'pending_review' => 'Menunggu review', 'pending' => 'Menunggu review', 'draft' => 'Draft', 'revision' => 'Perlu revisi', 'rejected' => 'Ditolak', 'active' => 'Aktif', 'inactive' => 'Tidak aktif', 'verified' => 'Terverifikasi', 'unverified' => 'Belum terverifikasi', 'published' => 'Terbit', 'unpublished' => 'Belum terbit', 'archived' => 'Diarsipkan', 'scheduled' => 'Terjadwal', 'ongoing' => 'Berlangsung', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan', 'planned' => 'Direncanakan', 'running' => 'Berjalan'][$value] ?? $value;
    }
}
