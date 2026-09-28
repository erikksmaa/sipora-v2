<?php

namespace Database\Seeders;

use App\Models\ActivityCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ActivityCategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Teknologi', 'Pendidikan', 'Olahraga', 'Kewirausahaan', 'Sosial', 'Kreatif'] as $name) {
            $category = ActivityCategory::withTrashed()->firstOrNew(['slug' => Str::slug($name)]);
            $category->fill([
                'name' => $name,
                'description' => 'Nilai master sementara khusus pengembangan; belum ditetapkan sebagai kategori resmi Dindikpora.',
            ]);
            $category->deleted_at = null;
            $category->save();
        }
    }
}
