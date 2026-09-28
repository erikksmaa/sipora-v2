<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\ActivityReview;
use App\Models\AdministrativeArea;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $manager = User::query()->where('email', 'youth1@sipora.test')->firstOrFail();
        $verifier = User::query()->where('email', 'verifier@sipora.test')->firstOrFail();
        $district = AdministrativeArea::query()->where('code', '33.27.08')->firstOrFail();
        $records = [
            ['slug' => 'kelas-dasar-ui-ux', 'title' => 'Kelas Dasar UI/UX', 'organization' => 'komunitas-programmer-pemalang', 'category' => 'kreatif', 'review' => 'draft', 'publication' => 'unpublished', 'execution' => 'scheduled', 'days' => 30],
            ['slug' => 'pelatihan-public-speaking-pemuda', 'title' => 'Pelatihan Public Speaking Pemuda', 'organization' => 'komunitas-programmer-pemalang', 'category' => 'pendidikan', 'review' => 'pending_review', 'publication' => 'unpublished', 'execution' => 'scheduled', 'days' => 24],
            ['slug' => 'kelas-konten-kreatif', 'title' => 'Kelas Konten Kreatif', 'organization' => 'komunitas-programmer-pemalang', 'category' => 'kreatif', 'review' => 'revision', 'publication' => 'unpublished', 'execution' => 'scheduled', 'days' => 20],
            ['slug' => 'seminar-keamanan-digital', 'title' => 'Seminar Keamanan Digital', 'organization' => 'komunitas-programmer-pemalang', 'category' => 'teknologi', 'review' => 'approved', 'publication' => 'unpublished', 'execution' => 'scheduled', 'days' => 18],
            ['slug' => 'workshop-web-development-pemula', 'title' => 'Workshop Web Development Pemula', 'organization' => 'komunitas-programmer-pemalang', 'category' => 'teknologi', 'review' => 'approved', 'publication' => 'published', 'execution' => 'scheduled', 'days' => 12],
            ['slug' => 'bootcamp-digital-pemalang', 'title' => 'Bootcamp Digital Pemalang', 'organization' => 'pemuda-olahraga-pemalang', 'category' => 'teknologi', 'review' => 'approved', 'publication' => 'published', 'execution' => 'ongoing', 'days' => -1],
            ['slug' => 'seminar-kewirausahaan-digital', 'title' => 'Seminar Kewirausahaan Digital', 'organization' => 'komunitas-programmer-pemalang', 'category' => 'kewirausahaan', 'review' => 'approved', 'publication' => 'archived', 'execution' => 'completed', 'days' => -45],
        ];

        foreach ($records as $record) {
            $organization = Organization::query()->where('slug', $record['organization'])->firstOrFail();
            $category = ActivityCategory::query()->where('slug', $record['category'])->firstOrFail();
            $start = now()->startOfHour()->addDays($record['days'])->setHour(9);
            $end = $record['slug'] === 'bootcamp-digital-pemalang' ? $start->copy()->addDays(2)->setHour(16) : $start->copy()->addHours(4);
            $activity = Activity::withTrashed()->updateOrCreate(['slug' => $record['slug']], [
                'organization_id' => $organization->getKey(), 'program_id' => null, 'category_id' => $category->getKey(),
                'created_by_user_id' => $manager->getKey(), 'title' => $record['title'],
                'description' => 'Activity pengembangan yang menggambarkan kegiatan pemuda di Kabupaten Pemalang.',
                'location_type' => 'offline', 'venue_name' => 'Gedung Pemuda Pemalang', 'administrative_area_id' => $district->getKey(),
                'address_text' => 'Kabupaten Pemalang', 'start_at' => $start, 'end_at' => $end,
                'registration_open_at' => $start->copy()->subDays(14), 'registration_close_at' => $start->copy()->subDay(),
                'quota' => 30, 'registration_mode' => 'approval_required', 'min_age' => 15, 'max_age' => 35,
                'requires_identity_verification' => false, 'members_only' => false, 'eligibility_notes' => 'Data contoh untuk pengujian lokal.',
                'certificate_enabled' => false, 'review_status' => $record['review'], 'publication_status' => $record['publication'],
                'execution_status' => $record['execution'], 'published_at' => $record['publication'] === 'published' ? now()->subWeek() : null,
                'deleted_at' => null,
            ]);

            if (in_array($record['review'], ['approved', 'revision', 'rejected'], true)) {
                ActivityReview::withTrashed()->updateOrCreate(
                    ['activity_id' => $activity->getKey(), 'decision' => $record['review']],
                    ['reviewer_id' => $verifier->getKey(), 'review_notes' => $record['review'] === 'revision' ? 'Perjelas hasil belajar dan susunan sesi.' : null,
                        'reviewed_at' => now()->subDays(10), 'deleted_at' => null]
                );
            }
        }
    }
}
