<?php

namespace Database\Seeders;

use App\Models\OrganizationExperience;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserEducation;
use App\Models\UserSkill;
use Illuminate\Database\Seeder;

class UserEnrichmentSeeder extends Seeder
{
    public function run(): void
    {
        $youth1 = User::query()->where('email', 'youth1@sipora.test')->firstOrFail();
        $youth2 = User::query()->where('email', 'youth2@sipora.test')->firstOrFail();

        $this->skill($youth1, 'pemrograman-web-software', 'advanced');
        $this->skill($youth1, 'manajemen-acara-kepemimpinan', 'intermediate');
        $this->skill($youth2, 'desain-grafis-uiux', 'intermediate');

        UserEducation::withTrashed()->updateOrCreate(
            ['user_id' => $youth1->getKey(), 'institution_name' => 'Universitas Pekalongan'],
            ['education_level' => 'S1', 'field_of_study' => 'Teknik Informatika', 'start_date' => '2020-08-01', 'end_date' => null, 'is_current' => true, 'description' => 'Data pendidikan contoh untuk pengembangan.', 'deleted_at' => null]
        );
        UserEducation::withTrashed()->updateOrCreate(
            ['user_id' => $youth2->getKey(), 'institution_name' => 'SMK Negeri 1 Pemalang'],
            ['education_level' => 'SMK', 'field_of_study' => 'Desain Komunikasi Visual', 'start_date' => '2021-07-01', 'end_date' => '2024-06-30', 'is_current' => false, 'description' => null, 'deleted_at' => null]
        );
        OrganizationExperience::withTrashed()->updateOrCreate(
            ['user_id' => $youth1->getKey(), 'organization_name' => 'Forum Pemuda Digital Pemalang'],
            ['role_title' => 'Koordinator Teknologi', 'start_date' => '2022-01-01', 'end_date' => null, 'is_current' => true, 'description' => 'Pengalaman eksternal yang dilaporkan sendiri.', 'deleted_at' => null]
        );
        UserAchievement::withTrashed()->updateOrCreate(
            ['user_id' => $youth1->getKey(), 'title' => 'Finalis Lomba Inovasi Digital Pemuda'],
            ['issuer_name' => 'Panitia Demo Lokal', 'achievement_date' => '2025-08-17', 'description' => 'Prestasi contoh dan belum diverifikasi SIPORA.', 'verification_status' => 'self_reported', 'deleted_at' => null]
        );
    }

    private function skill(User $user, string $slug, string $level): void
    {
        $skill = Skill::query()->where('slug', $slug)->firstOrFail();
        UserSkill::withTrashed()->updateOrCreate(
            ['user_id' => $user->getKey(), 'skill_id' => $skill->getKey()],
            ['proficiency_level' => $level, 'is_self_reported' => true, 'deleted_at' => null]
        );
    }
}
