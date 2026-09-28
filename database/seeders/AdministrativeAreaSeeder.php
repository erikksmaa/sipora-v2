<?php

namespace Database\Seeders;

use App\Models\AdministrativeArea;
use Illuminate\Database\Seeder;

class AdministrativeAreaSeeder extends Seeder
{
    public function run(): void
    {
        $province = $this->area('33', 'Jawa Tengah', 'province');
        $regency = $this->area('33.27', 'Kabupaten Pemalang', 'regency', $province->getKey());

        foreach ([
            '33.27.01' => 'Moga', '33.27.02' => 'Pulosari', '33.27.03' => 'Belik', '33.27.04' => 'Watukumpul',
            '33.27.05' => 'Bodeh', '33.27.06' => 'Bantarbolang', '33.27.07' => 'Randudongkal', '33.27.08' => 'Pemalang',
            '33.27.09' => 'Taman', '33.27.10' => 'Petarukan', '33.27.11' => 'Ampelgading', '33.27.12' => 'Comal',
            '33.27.13' => 'Ulujami', '33.27.14' => 'Warungpring',
        ] as $code => $name) {
            $this->area($code, $name, 'district', $regency->getKey());
        }
    }

    private function area(string $code, string $name, string $level, ?string $parentId = null): AdministrativeArea
    {
        $area = AdministrativeArea::withTrashed()->firstOrNew(['code' => $code]);
        $area->fill(['name' => $name, 'area_level' => $level, 'parent_id' => $parentId]);
        $area->deleted_at = null;
        $area->save();

        return $area;
    }
}
