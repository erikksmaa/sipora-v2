<?php

namespace Database\Seeders;

use App\Models\Interest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InterestSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Teknologi', 'Olahraga', 'Kewirausahaan', 'Seni & Kreatif', 'Pendidikan', 'Lingkungan', 'Sosial', 'Leadership'] as $name) {
            $interest = Interest::withTrashed()->firstOrNew(['slug' => Str::slug($name)]);
            $interest->name = $name;
            $interest->deleted_at = null;
            $interest->save();
        }
    }
}
