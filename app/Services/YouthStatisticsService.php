<?php

namespace App\Services;

use App\Models\ActivityParticipation;
use App\Models\Activity;
use App\Models\AdministrativeArea;
use App\Models\Organization;
use App\Models\User;
use App\Models\UserCertificate;
use App\Models\UserIdentity;
use App\Services\Discovery\ProgramDiscoveryService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final class YouthStatisticsService
{
    public const PUBLIC_THRESHOLD = 5;

    public const AGE_BUCKETS = ['under_15' => '<15', '15_19' => '15–19', '20_24' => '20–24', '25_29' => '25–29', '30_34' => '30–34', '35_plus' => '35+'];

    public function __construct(private readonly ProgramDiscoveryService $programs) {}

    /** @return array<string,int> */
    public function summary(array $filters = []): array
    {
        return [
            'registered' => $this->youth($filters)->count(),
            'identity_verified' => $this->youth($filters)->whereHas('identity', fn (Builder $identity) => $identity->where('verification_status', UserIdentity::STATUS_VERIFIED))->count(),
            'public_portfolio' => $this->youth($filters)->whereHas('profile')->whereHas('profileVisibility', fn (Builder $visibility) => $visibility->where('is_profile_public', true))->count(),
            'activity_participated' => $this->youth($filters)->whereHas('activityParticipations', fn (Builder $participation) => $participation->where('registration_status', ActivityParticipation::REGISTRATION_ACCEPTED))->count(),
            'certificate' => $this->youth($filters)->whereHas('certificates', fn (Builder $certificate) => $certificate->where('source_type', UserCertificate::SOURCE_SIPORA)->where('verification_status', UserCertificate::STATUS_VERIFIED))->count(),
        ];
    }

    /** @return array<string, mixed> */
    public function public(array $filters = []): array
    {
        $data = $this->aggregate($filters);
        foreach (['growth_monthly', 'growth_yearly', 'age', 'gender', 'districts', 'interests', 'skills'] as $key) {
            $data[$key] = $this->suppress($data[$key]);
        }
        $data['funnel'] = $this->suppress($data['funnel']);
        foreach ($data['ecosystem'] as $domain => $rows) {
            $data['ecosystem'][$domain] = $this->suppress($rows);
        }
        $data['totals'] = array_map(fn (int $count): ?int => $this->publicCount($count), $data['totals']);

        return $data;
    }

    /** @return array<string, mixed> */
    public function admin(): array
    {
        return $this->aggregate();
    }

    /** @return array<string, mixed> */
    private function aggregate(array $filters = []): array
    {
        $profileComplete = fn (Builder $query): Builder => $query
            ->whereHas('profile', fn (Builder $profile) => $profile
                ->whereNotNull('full_name')->whereNotNull('birth_date')
                ->whereNotNull('bio')->where('bio', '<>', '')
                ->whereNotNull('occupation_status'))
            ->whereHas('primaryDomicile');
        $identityVerified = fn (Builder $query): Builder => $query
            ->whereHas('identity', fn (Builder $identity) => $identity->where('verification_status', UserIdentity::STATUS_VERIFIED));
        $participated = fn (Builder $query): Builder => $query
            ->whereHas('activityParticipations', fn (Builder $participation) => $participation
                ->where('registration_status', ActivityParticipation::REGISTRATION_ACCEPTED));
        $completed = fn (Builder $query): Builder => $query
            ->whereHas('activityParticipations', fn (Builder $participation) => $participation
                ->where('registration_status', ActivityParticipation::REGISTRATION_ACCEPTED)
                ->where('completion_status', ActivityParticipation::COMPLETION_COMPLETED));
        $certified = fn (Builder $query): Builder => $query
            ->whereHas('certificates', fn (Builder $certificate) => $certificate
                ->where('source_type', UserCertificate::SOURCE_SIPORA)
                ->where('verification_status', UserCertificate::STATUS_VERIFIED));

        $totals = $this->summary($filters);

        $funnelQuery = $profileComplete($this->youth($filters));
        $funnel = [['label' => 'Terdaftar', 'count' => $totals['registered']]];
        $funnel[] = ['label' => 'Profil lengkap', 'count' => (clone $funnelQuery)->count()];
        $funnelQuery = $identityVerified($funnelQuery);
        $funnel[] = ['label' => 'Identitas terverifikasi', 'count' => (clone $funnelQuery)->count()];
        $funnelQuery = $participated($funnelQuery);
        $funnel[] = ['label' => 'Mengikuti Activity', 'count' => (clone $funnelQuery)->count()];
        $funnelQuery = $completed($funnelQuery);
        $funnel[] = ['label' => 'Activity selesai', 'count' => (clone $funnelQuery)->count()];
        $funnel[] = ['label' => 'Sertifikat SIPORA', 'count' => $certified($funnelQuery)->count()];

        $youthIds = $this->youth($filters)->select('users.id');
        $firstMonth = isset($filters['year']) ? now()->setDate((int) $filters['year'], 1, 1)->startOfMonth() : now()->startOfMonth()->subMonths(11);
        $monthly = $this->youth($filters)->where('users.created_at', '>=', $firstMonth)
            ->where('users.created_at', '<', $firstMonth->copy()->addMonths(12))
            ->selectRaw("DATE_FORMAT(users.created_at, '%Y-%m') AS label, COUNT(*) AS total")
            ->groupBy('label')->orderBy('label')->get();
        $yearly = $this->youth($filters)->selectRaw('YEAR(users.created_at) AS label, COUNT(*) AS total')
            ->groupBy('label')->orderBy('label')->get();
        $ages = \App\Models\UserProfile::query()->whereIn('user_id', $youthIds)
            ->where('birth_date', '<=', today())
            ->selectRaw("CASE WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 15 THEN '<15' WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 15 AND 19 THEN '15–19' WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 20 AND 24 THEN '20–24' WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 25 AND 29 THEN '25–29' WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 30 AND 34 THEN '30–34' ELSE '35+' END AS label, COUNT(*) AS total")
            ->groupBy('label')->orderBy('label')->get();
        $gender = \App\Models\UserProfile::query()->whereIn('user_id', $youthIds)
            ->selectRaw("COALESCE(gender, 'unknown') AS label, COUNT(*) AS total")
            ->groupBy('label')->orderBy('label')->get();
        $districts = \Illuminate\Support\Facades\DB::table('user_addresses AS addresses')
            ->join('administrative_areas AS area', 'area.id', '=', 'addresses.administrative_area_id')
            ->leftJoin('administrative_areas AS parent', 'parent.id', '=', 'area.parent_id')
            ->whereNull('addresses.deleted_at')->whereNull('area.deleted_at')
            ->where('addresses.address_type', 'domicile')->where('addresses.is_primary', true)
            ->whereIn('addresses.user_id', $youthIds)
            ->where(fn ($query) => $query->where('area.area_level', 'district')->orWhere(fn ($village) => $village->where('area.area_level', 'village')->where('parent.area_level', 'district')->whereNull('parent.deleted_at')))
            ->selectRaw("CASE WHEN area.area_level = 'district' THEN area.name ELSE parent.name END AS label, COUNT(DISTINCT addresses.user_id) AS total")
            ->groupBy('label')->orderByDesc('total')->orderBy('label')->get();
        $interests = \Illuminate\Support\Facades\DB::table('user_interests AS pivot')
            ->join('interests AS item', 'item.id', '=', 'pivot.interest_id')
            ->whereNull('pivot.deleted_at')->whereNull('item.deleted_at')->whereIn('pivot.user_id', $youthIds)
            ->selectRaw('item.name AS label, COUNT(DISTINCT pivot.user_id) AS total')
            ->groupBy('item.id', 'item.name')->orderByDesc('total')->orderBy('item.name')->limit(10)->get();
        $skills = \Illuminate\Support\Facades\DB::table('user_skills AS pivot')
            ->join('skills AS item', 'item.id', '=', 'pivot.skill_id')
            ->whereNull('pivot.deleted_at')->whereNull('item.deleted_at')->whereIn('pivot.user_id', $youthIds)
            ->selectRaw('item.name AS label, COUNT(DISTINCT pivot.user_id) AS total')
            ->groupBy('item.id', 'item.name')->orderByDesc('total')->orderBy('item.name')->limit(10)->get();

        return [
            'totals' => $totals,
            'funnel' => $funnel,
            'growth_monthly' => $this->monthlyRows($monthly, $firstMonth),
            'growth_yearly' => $this->rows($yearly),
            'age' => $this->rows($ages),
            'gender' => $this->rows($gender),
            'districts' => $this->rows($districts),
            'interests' => $this->rows($interests),
            'skills' => $this->rows($skills),
            'ecosystem' => $this->ecosystem(),
        ];
    }

    /** @return array{years:list<int>,districts:list<array{code:string,name:string}>,ages:array<string,string>} */
    public function filterOptions(): array
    {
        return [
            'years' => $this->youth()->selectRaw('YEAR(users.created_at) AS year')->groupBy('year')->orderByDesc('year')->pluck('year')->map(fn ($year): int => (int) $year)->all(),
            'districts' => AdministrativeArea::query()->where('area_level', 'district')->orderBy('name')->get(['code', 'name'])->map(fn ($area): array => ['code' => $area->code, 'name' => $area->name])->all(),
            'ages' => self::AGE_BUCKETS,
        ];
    }

    private function youth(array $filters = []): Builder
    {
        $query = User::query()->whereHas('roles', fn (Builder $role) => $role->where('name', 'youth'));
        if (isset($filters['year'])) {
            $query->whereYear('users.created_at', (int) $filters['year']);
        }
        if (isset($filters['district'])) {
            $query->whereHas('primaryDomicile.administrativeArea', fn (Builder $area) => $area
                ->where(fn (Builder $district) => $district->where('code', $filters['district'])
                    ->orWhereHas('parent', fn (Builder $parent) => $parent->where('code', $filters['district']))));
        }
        if (isset($filters['age'])) {
            [$min, $max] = match ($filters['age']) {
                'under_15' => [0, 14], '15_19' => [15, 19], '20_24' => [20, 24],
                '25_29' => [25, 29], '30_34' => [30, 34], '35_plus' => [35, 200],
            };
            $query->whereHas('profile', fn (Builder $profile) => $profile->whereDate('birth_date', '<=', today())
                ->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN ? AND ?', [$min, $max]));
        }

        return $query;
    }

    private function ecosystem(): array
    {
        $activity = Activity::query()->where('review_status', Activity::REVIEW_APPROVED)->where('publication_status', Activity::PUBLICATION_PUBLISHED)
            ->selectRaw('execution_status AS label, COUNT(*) AS total')->groupBy('execution_status')->get();
        $community = Organization::query()->where('review_status', Organization::REVIEW_APPROVED)
            ->selectRaw('operational_status AS label, COUNT(*) AS total')->groupBy('operational_status')->get();
        $program = $this->programs->publicQuery()->setEagerLoads([])
            ->selectRaw('execution_status AS label, COUNT(*) AS total')->groupBy('execution_status')->get();

        return ['activity' => $this->rows($activity), 'community' => $this->rows($community), 'program' => $this->rows($program)];
    }

    private function publicCount(int $count): ?int
    {
        return $count === 0 || $count >= self::PUBLIC_THRESHOLD ? $count : null;
    }

    private function suppress(array $rows): array
    {
        return array_map(fn (array $row): array => ['label' => $row['label'], 'count' => $this->publicCount($row['count'])], $rows);
    }

    /** @return list<array{label:string,count:int}> */
    private function rows(Collection $rows): array
    {
        return $rows->map(fn ($row): array => ['label' => (string) $row->label, 'count' => (int) $row->total])->all();
    }

    /** @return list<array{label:string,count:int}> */
    private function monthlyRows(Collection $rows, \Carbon\CarbonInterface $first): array
    {
        $counts = $rows->pluck('total', 'label');

        return collect(range(0, 11))->map(fn (int $offset): array => [
            'label' => $first->copy()->addMonths($offset)->format('Y-m'),
            'count' => (int) ($counts[$first->copy()->addMonths($offset)->format('Y-m')] ?? 0),
        ])->all();
    }
}
