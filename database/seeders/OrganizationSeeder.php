<?php

namespace Database\Seeders;

use App\Models\AdministrativeArea;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationVerificationRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Komunitas Teknologi', 'Komunitas Olahraga', 'Komunitas Kreatif', 'Komunitas Kewirausahaan'] as $name) {
            $category = OrganizationCategory::withTrashed()->firstOrNew(['slug' => Str::slug($name)]);
            $category->fill(['name' => $name, 'description' => 'Kategori komunitas contoh khusus pengembangan.']);
            $category->deleted_at = null;
            $category->save();
        }

        $verifier = User::query()->where('email', 'verifier@sipora.test')->firstOrFail();
        $records = [
            ['slug' => 'komunitas-programmer-pemalang', 'name' => 'Komunitas Programmer Pemalang', 'owner' => 'youth1@sipora.test', 'category' => 'komunitas-teknologi', 'review' => 'approved', 'operational' => 'active', 'district' => '33.27.08'],
            ['slug' => 'pemuda-olahraga-pemalang', 'name' => 'Pemuda Olahraga Pemalang', 'owner' => 'youth3@sipora.test', 'category' => 'komunitas-olahraga', 'review' => 'approved', 'operational' => 'active', 'district' => '33.27.12'],
            ['slug' => 'forum-kreatif-pemalang', 'name' => 'Forum Kreatif Pemalang', 'owner' => 'youth2@sipora.test', 'category' => 'komunitas-kreatif', 'review' => 'pending_review', 'operational' => 'inactive'],
            ['slug' => 'wirausaha-muda-pemalang', 'name' => 'Wirausaha Muda Pemalang', 'owner' => 'youth2@sipora.test', 'category' => 'komunitas-kewirausahaan', 'review' => 'revision', 'operational' => 'inactive'],
            ['slug' => 'sahabat-kreatif-pesisir', 'name' => 'Sahabat Kreatif Pesisir', 'owner' => 'youth3@sipora.test', 'category' => 'komunitas-kreatif', 'review' => 'rejected', 'operational' => 'inactive'],
        ];

        foreach ($records as $record) {
            $owner = User::query()->where('email', $record['owner'])->firstOrFail();
            $category = OrganizationCategory::query()->where('slug', $record['category'])->firstOrFail();
            $district = AdministrativeArea::query()->where('code', $record['district'] ?? '33.27.09')->firstOrFail();
            $approved = $record['review'] === 'approved';
            $organization = Organization::withTrashed()->updateOrCreate(['slug' => $record['slug']], [
                'created_by_user_id' => $owner->getKey(), 'category_id' => $category->getKey(), 'administrative_area_id' => $district->getKey(),
                'name' => $record['name'], 'description' => 'Komunitas contoh dengan aktivitas kepemudaan di Kabupaten Pemalang.',
                'contact_email' => $record['slug'].'@example.test', 'contact_phone' => '080000000200', 'address_text' => 'Kabupaten Pemalang',
                'social_links' => ['instagram' => 'https://example.test/'.$record['slug']], 'review_status' => $record['review'],
                'operational_status' => $record['operational'], 'approved_at' => $approved ? now()->subMonths(3) : null,
                'approved_by' => $approved ? $verifier->getKey() : null, 'deleted_at' => null,
            ]);

            if ($record['review'] !== 'draft') {
                $requestStatus = $record['review'] === 'pending_review' ? 'pending' : $record['review'];
                OrganizationVerificationRequest::withTrashed()->updateOrCreate(
                    ['organization_id' => $organization->getKey(), 'status' => $requestStatus],
                    ['submitted_by' => $owner->getKey(), 'submission_notes' => 'Pengajuan contoh untuk pengembangan.', 'submitted_at' => now()->subMonths(3),
                        'reviewed_by' => $requestStatus === 'pending' ? null : $verifier->getKey(), 'reviewed_at' => $requestStatus === 'pending' ? null : now()->subMonths(3)->addDay(),
                        'review_notes' => $requestStatus === 'revision' ? 'Lengkapi deskripsi dan kontak komunitas.' : ($requestStatus === 'rejected' ? 'Dokumen organisasi belum memenuhi ketentuan contoh.' : null),
                        'deleted_at' => null]
                );
            }
        }
    }
}
