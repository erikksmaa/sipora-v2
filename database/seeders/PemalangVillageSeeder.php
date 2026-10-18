<?php

namespace Database\Seeders;

use App\Models\AdministrativeArea;
use Illuminate\Database\Seeder;

final class PemalangVillageSeeder extends Seeder
{
    public function run(): void
    {
        $data = json_decode(file_get_contents(database_path('data/pemalang-villages.json')), true, flags: JSON_THROW_ON_ERROR)['areas'];
        $districts = collect($data)->filter(fn ($a) => strlen($a['code']) === 8)->keyBy('code');
        foreach ($data as $area) {
            if (strlen($area['code']) !== 13) {
                continue;
            }
            $name = $districts[substr($area['code'], 0, 8)]['name'];
            $parent = AdministrativeArea::where('area_level', 'district')->where('name', $name)->whereHas('parent', fn ($q) => $q->where('code', '33.27'))->first();
            if (! $parent) {
                continue;
            }
            AdministrativeArea::firstOrCreate(['code' => $area['code']], ['name' => $area['name'], 'area_level' => 'village', 'parent_id' => $parent->getKey()]);
        }
    }
}
