<?php

namespace Database\Seeders;

use App\Models\ProgramCategory;
use Illuminate\Database\Seeder;

class ProgramCategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['slug' => 'pengembangan-kapasitas-pemuda', 'name' => 'Pengembangan Kapasitas Pemuda'],
            ['slug' => 'kepemudaan-dan-kepemimpinan', 'name' => 'Kepemudaan dan Kepemimpinan'],
            ['slug' => 'olahraga-masyarakat', 'name' => 'Olahraga Masyarakat'],
        ] as $record) {
            ProgramCategory::withTrashed()->updateOrCreate(
                ['slug' => $record['slug']],
                ['name' => $record['name'], 'description' => 'Kategori contoh khusus lingkungan pengembangan; bukan master resmi Dindikpora.', 'deleted_at' => null],
            );
        }
    }
}
