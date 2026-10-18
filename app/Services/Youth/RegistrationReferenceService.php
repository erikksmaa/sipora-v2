<?php

namespace App\Services\Youth;

use App\Models\AdministrativeArea;
use App\Models\Interest;
use App\Support\BinaryUuid;

final class RegistrationReferenceService
{
    public function data(): array
    {
        $districts = AdministrativeArea::where('area_level', 'district')->whereHas('parent', fn ($q) => $q->where('code', '33.27'))->orderBy('name')->get();
        $villages = AdministrativeArea::where('area_level', 'village')->whereIn('parent_id', $districts->modelKeys())->orderBy('name')->get();

        return ['districts' => $districts->map(fn ($a) => ['id' => $a->uuid(), 'name' => $a->name])->values()->all(), 'villages' => $villages->map(fn ($a) => ['id' => $a->uuid(), 'district_id' => BinaryUuid::text($a->parent_id), 'name' => $a->name])->values()->all(), 'interests' => Interest::orderBy('name')->get()->map(fn ($i) => ['id' => $i->uuid(), 'name' => $i->name])->values()->all(), 'minAge' => config('youth.min_age'), 'maxAge' => config('youth.max_age'), 'today' => now()->toDateString()];
    }
}
