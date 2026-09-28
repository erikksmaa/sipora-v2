<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'Pemrograman Web & Software', 'Desain Grafis & UI/UX', 'Public Speaking & Komunikasi',
            'Manajemen Acara & Kepemimpinan', 'Penulisan Kreatif & Jurnalistik', 'Fotografi & Videografi',
            'Pemasaran Digital & Media Sosial', 'Analisis Data & Riset', 'Kewirausahaan & Pengelolaan Bisnis',
            'Kerelawanan & Tanggap Bencana', 'Olahraga & Kebugaran Fisik', 'Seni Musik & Tari Tradisional',
        ] as $name) {
            $skill = Skill::withTrashed()->firstOrNew(['slug' => Str::slug($name)]);
            $skill->name = $name;
            $skill->deleted_at = null;
            $skill->save();
        }
    }
}
