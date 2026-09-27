<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Support\BinaryUuid;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class YouthEnrichmentSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            'Pemrograman Web & Software',
            'Desain Grafis & UI/UX',
            'Public Speaking & Komunikasi',
            'Manajemen Acara & Kepemimpinan',
            'Penulisan Kreatif & Jurnalistik',
            'Fotografi & Videografi',
            'Pemasaran Digital & Media Sosial',
            'Analisis Data & Riset',
            'Kewirausahaan & Pengelolaan Bisnis',
            'Kerelawanan & Tanggap Bencana',
            'Olahraga & Kebugaran Fisik',
            'Seni Musik & Tari Tradisional',
        ];

        foreach ($skills as $name) {
            $skill = Skill::withTrashed()->firstOrNew(['slug' => Str::slug($name)]);
            if (! $skill->exists) {
                $skill->setAttribute('id', BinaryUuid::generate());
            }
            $skill->fill(['name' => $name])->setAttribute('deleted_at', null)->save();
        }
    }
}
