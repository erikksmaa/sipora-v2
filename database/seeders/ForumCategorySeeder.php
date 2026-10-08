<?php

namespace Database\Seeders;

use App\Models\ForumCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ForumCategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Teknologi', 'Pendidikan', 'Karier', 'Kewirausahaan', 'Organisasi & Community', 'Kreativitas', 'Olahraga', 'Volunteer & Sosial', 'Diskusi Umum'] as $name) {
            ForumCategory::query()->updateOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'is_active' => true]);
        }
    }
}
