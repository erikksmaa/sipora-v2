<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class YouthEnrichmentSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(SkillSeeder::class);
    }
}
