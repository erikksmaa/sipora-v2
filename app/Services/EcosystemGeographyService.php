<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\ActivityParticipation;
use App\Models\AdministrativeArea;
use App\Models\Organization;
use App\Services\Discovery\ProgramDiscoveryService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class EcosystemGeographyService
{
    public function __construct(private readonly ProgramDiscoveryService $programs) {}

    /**
     * Counts are keyed by the existing kecamatan code. No individual address or
     * coordinates leave this service; records without a valid district are omitted.
     *
     * @return list<array{code:string,district:string,youth:int,participated:int,community:int,activity:int,program:int}>
     */
    public function coverage(Builder $youth): array
    {
        $districts = AdministrativeArea::query()->where('area_level', 'district')->orderBy('name')->get(['code', 'name']);
        $youthCounts = $this->domicileCounts($youth, false);
        $participationCounts = $this->domicileCounts($youth, true);
        $communities = $this->areaCounts('organizations', 'community', 'community.administrative_area_id', fn ($query) => $query
            ->where('community.review_status', Organization::REVIEW_APPROVED)
            ->where('community.operational_status', Organization::OPERATIONAL_ACTIVE));
        $activities = $this->areaCounts('activities', 'activity', 'activity.administrative_area_id', fn ($query) => $query
            ->where('activity.review_status', Activity::REVIEW_APPROVED)
            ->where('activity.publication_status', Activity::PUBLICATION_PUBLISHED));
        $programs = $this->areaCounts('programs', 'program', 'community.administrative_area_id', fn ($query) => $query
            ->join('organizations AS community', 'community.id', '=', 'program.organization_id')
            ->whereNull('community.deleted_at')
            ->whereIn('program.id', $this->programs->publicQuery()->setEagerLoads([])->select('programs.id')));

        return $districts->map(fn ($district): array => [
            'code' => $district->code,
            'district' => $district->name,
            'youth' => (int) ($youthCounts[$district->code] ?? 0),
            'participated' => (int) ($participationCounts[$district->code] ?? 0),
            'community' => (int) ($communities[$district->code] ?? 0),
            'activity' => (int) ($activities[$district->code] ?? 0),
            'program' => (int) ($programs[$district->code] ?? 0),
        ])->all();
    }

    /** @return list<array<string,string|int|null>> */
    public function suppress(array $rows, int $threshold): array
    {
        return array_map(function (array $row) use ($threshold): array {
            foreach (['youth', 'participated', 'community', 'activity', 'program'] as $key) {
                if ($row[$key] > 0 && $row[$key] < $threshold) {
                    $row[$key] = null;
                }
            }

            return $row;
        }, $rows);
    }

    private function domicileCounts(Builder $youth, bool $participated): Collection
    {
        $query = DB::table('user_addresses AS address')
            ->join('administrative_areas AS area', 'area.id', '=', 'address.administrative_area_id')
            ->leftJoin('administrative_areas AS parent', 'parent.id', '=', 'area.parent_id')
            ->whereNull('address.deleted_at')->whereNull('area.deleted_at')
            ->where('address.address_type', 'domicile')->where('address.is_primary', true)
            ->whereIn('address.user_id', (clone $youth)->select('users.id'));
        if ($participated) {
            $query->join('activity_participations AS participation', 'participation.user_id', '=', 'address.user_id')
                ->whereNull('participation.deleted_at')
                ->where('participation.registration_status', ActivityParticipation::REGISTRATION_ACCEPTED);
        }

        return $this->districtScope($query)
            ->selectRaw("CASE WHEN area.area_level = 'district' THEN area.code ELSE parent.code END AS district_code, COUNT(DISTINCT ".($participated ? 'participation.user_id' : 'address.user_id').") AS total")
            ->groupBy('district_code')->pluck('total', 'district_code');
    }

    private function areaCounts(string $table, string $alias, string $areaColumn, callable $scope): Collection
    {
        $query = DB::table($table.' AS '.$alias)->whereNull($alias.'.deleted_at');
        $scope($query);

        return $this->districtScope($query
            ->join('administrative_areas AS area', 'area.id', '=', DB::raw($areaColumn))
            ->leftJoin('administrative_areas AS parent', 'parent.id', '=', 'area.parent_id'))
            ->selectRaw("CASE WHEN area.area_level = 'district' THEN area.code ELSE parent.code END AS district_code, COUNT(DISTINCT {$alias}.id) AS total")
            ->groupBy('district_code')->pluck('total', 'district_code');
    }

    private function districtScope(QueryBuilder $query): QueryBuilder
    {
        return $query->whereNull('area.deleted_at')
            ->where(fn ($district) => $district->where('area.area_level', 'district')
                ->orWhere(fn ($village) => $village->where('area.area_level', 'village')
                    ->where('parent.area_level', 'district')->whereNull('parent.deleted_at')));
    }
}
