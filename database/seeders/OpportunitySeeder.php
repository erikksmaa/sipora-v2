<?php

namespace Database\Seeders;

use App\Models\AdministrativeArea;
use App\Models\Opportunity;
use App\Models\OpportunityCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class OpportunitySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->where('email', 'admin@sipora.test')->firstOrFail();
        $area = AdministrativeArea::query()->where('code', '33.27.08')->firstOrFail();
        $records = [
            ['slug' => 'beasiswa-pemuda-pemalang', 'title' => 'Beasiswa Pengembangan Pemuda Pemalang', 'category' => 'beasiswa', 'provider' => 'Dindikpora Kabupaten Pemalang', 'days' => 21, 'status' => Opportunity::STATUS_PUBLISHED],
            ['slug' => 'magang-digital-pemalang', 'title' => 'Magang Digital untuk Pemuda', 'category' => 'magang', 'provider' => 'Mitra SIPORA', 'days' => 35, 'status' => Opportunity::STATUS_PUBLISHED],
            ['slug' => 'volunteer-festival-pemuda', 'title' => 'Volunteer Festival Pemuda', 'category' => 'volunteer', 'provider' => 'Forum Pemuda Pemalang', 'days' => 14, 'status' => Opportunity::STATUS_PUBLISHED],
            ['slug' => 'kompetisi-inovasi-pemuda', 'title' => 'Kompetisi Inovasi Pemuda', 'category' => 'kompetisi', 'provider' => 'Dindikpora Kabupaten Pemalang', 'days' => 45, 'status' => Opportunity::STATUS_DRAFT],
            ['slug' => 'pelatihan-kepemimpinan-arsip', 'title' => 'Pelatihan Kepemimpinan Pemuda', 'category' => 'pelatihan', 'provider' => 'Dindikpora Kabupaten Pemalang', 'days' => -30, 'status' => Opportunity::STATUS_ARCHIVED],
        ];

        foreach ($records as $record) {
            $category = OpportunityCategory::query()->where('slug', $record['category'])->firstOrFail();
            $deadline = now()->startOfHour()->addDays($record['days']);
            Opportunity::withTrashed()->updateOrCreate(['slug' => $record['slug']], [
                'category_id' => $category->getKey(),
                'organization_id' => null,
                'created_by_user_id' => $admin->getKey(),
                'title' => $record['title'],
                'provider_name' => $record['provider'],
                'description' => 'Data contoh pengembangan untuk menguji discovery Opportunity dan tautan pendaftaran eksternal.',
                'administrative_area_id' => $area->getKey(),
                'location_text' => 'Kabupaten Pemalang',
                'external_url' => 'https://opportunity.sipora.test/'.$record['slug'],
                'deadline_at' => $deadline,
                'starts_at' => $deadline->copy()->addDays(7),
                'ends_at' => $deadline->copy()->addDays(14),
                'publication_status' => $record['status'],
                'published_at' => $record['status'] === Opportunity::STATUS_PUBLISHED ? now()->subDays(2) : null,
                'deleted_at' => null,
            ]);
        }
    }
}
