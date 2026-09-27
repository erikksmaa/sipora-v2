<?php

namespace Database\Seeders;

use App\Models\AdministrativeArea;
use App\Models\Interest;
use App\Support\BinaryUuid;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class YouthFoundationSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Teknologi', 'Olahraga', 'Kewirausahaan', 'Seni & Kreatif', 'Pendidikan', 'Lingkungan', 'Sosial', 'Leadership'] as $name) {
            $interest = Interest::withTrashed()->firstOrNew(['slug' => Str::slug($name)]);
            if (! $interest->exists) {
                $interest->setAttribute('id', BinaryUuid::generate());
            }
            $interest->fill(['name' => $name])->setAttribute('deleted_at', null)->save();
        }

        $province = $this->area('33', 'Jawa Tengah', 'province');
        $regency = $this->area('33.27', 'Kabupaten Pemalang', 'regency', $province->getKey());

        $districts = [
            '33.27.01' => 'Moga', '33.27.02' => 'Pulosari', '33.27.03' => 'Belik', '33.27.04' => 'Watukumpul',
            '33.27.05' => 'Bodeh', '33.27.06' => 'Bantarbolang', '33.27.07' => 'Randudongkal', '33.27.08' => 'Pemalang',
            '33.27.09' => 'Taman', '33.27.10' => 'Petarukan', '33.27.11' => 'Ampelgading', '33.27.12' => 'Comal',
            '33.27.13' => 'Ulujami', '33.27.14' => 'Warungpring',
        ];
        foreach ($districts as $code => $name) {
            $this->area($code, $name, 'district', $regency->getKey());
        }
    }

    private function area(string $code, string $name, string $level, ?string $parentId = null): AdministrativeArea
    {
        $area = AdministrativeArea::withTrashed()->firstOrNew(['code' => $code]);
        if (! $area->exists) {
            $area->setAttribute('id', BinaryUuid::generate());
        }
        $area->fill(['name' => $name, 'area_level' => $level, 'parent_id' => $parentId])->setAttribute('deleted_at', null)->save();

        return $area;
    }
}
