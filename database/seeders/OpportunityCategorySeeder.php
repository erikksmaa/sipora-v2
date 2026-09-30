<?php

namespace Database\Seeders;

use App\Models\OpportunityCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OpportunityCategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Magang', 'Beasiswa', 'Kompetisi', 'Volunteer', 'Pelatihan'] as $name) {
            $category = OpportunityCategory::withTrashed()->firstOrNew(['slug' => Str::slug($name)]);
            $category->fill([
                'name' => $name,
                'description' => 'Nilai master sementara khusus pengembangan; belum ditetapkan sebagai kategori resmi Dindikpora.',
            ]);
            $category->deleted_at = null;
            $category->save();
        }
    }
}
