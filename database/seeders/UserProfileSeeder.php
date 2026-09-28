<?php

namespace Database\Seeders;

use App\Models\AdministrativeArea;
use App\Models\Interest;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserInterest;
use App\Models\UserProfile;
use App\Models\UserProfileVisibility;
use Illuminate\Database\Seeder;

class UserProfileSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = [
            'youth1@sipora.test' => ['public_slug' => 'erik-kusuma-rais', 'full_name' => 'Erik Kusuma Rais', 'birth_place' => 'Pemalang', 'birth_date' => '2001-05-14', 'gender' => 'male', 'phone' => '080000000101', 'bio' => 'Pengembang web muda yang aktif membangun ekosistem teknologi Pemalang.', 'occupation_status' => 'university_student', 'occupation_title' => 'Mahasiswa Teknik Informatika', 'district' => '33.27.08', 'interests' => ['teknologi', 'pendidikan', 'leadership']],
            'youth2@sipora.test' => ['public_slug' => 'nabila-putri-salsabila', 'full_name' => 'Nabila Putri Salsabila', 'birth_place' => 'Pemalang', 'birth_date' => '2004-09-22', 'gender' => 'female', 'phone' => '080000000102', 'bio' => 'Pemuda yang tertarik pada desain dan komunikasi publik.', 'occupation_status' => 'student', 'occupation_title' => 'Pelajar', 'district' => '33.27.09', 'interests' => ['seni-kreatif', 'teknologi']],
            'youth3@sipora.test' => ['public_slug' => 'bagas-prasetyo-utomo', 'full_name' => 'Bagas Prasetyo Utomo', 'birth_place' => 'Pemalang', 'birth_date' => '2002-02-11', 'gender' => 'male', 'phone' => null, 'bio' => null, 'occupation_status' => 'worker', 'occupation_title' => null, 'district' => '33.27.12', 'interests' => ['olahraga']],
            'youth4@sipora.test' => ['public_slug' => 'dinda-ayu-lestari', 'full_name' => 'Dinda Ayu Lestari', 'birth_place' => 'Pemalang', 'birth_date' => '2003-07-08', 'gender' => 'female', 'phone' => null, 'bio' => null, 'occupation_status' => 'student', 'occupation_title' => null, 'district' => '33.27.08', 'interests' => ['pendidikan']],
            'youth5@sipora.test' => ['public_slug' => 'rizky-aditya', 'full_name' => 'Rizky Aditya', 'birth_place' => 'Pemalang', 'birth_date' => '2000-12-02', 'gender' => 'male', 'phone' => null, 'bio' => null, 'occupation_status' => 'entrepreneur', 'occupation_title' => null, 'district' => '33.27.12', 'interests' => ['kewirausahaan']],
        ];

        foreach ($profiles as $email => $data) {
            $user = User::query()->where('email', $email)->firstOrFail();
            $district = AdministrativeArea::query()->where('code', $data['district'])->firstOrFail();
            $profile = collect($data)->except(['district', 'interests'])->all();
            UserProfile::withTrashed()->updateOrCreate(['user_id' => $user->getKey()], $profile + ['deleted_at' => null]);
            UserProfileVisibility::withTrashed()->updateOrCreate(['user_id' => $user->getKey()], [
                'is_profile_public' => true, 'show_photo' => true, 'show_bio' => true, 'show_interests' => true,
                'show_skills' => true, 'show_education' => true, 'show_organization_experience' => true,
                'show_achievements' => true, 'deleted_at' => null,
            ]);
            UserAddress::withTrashed()->updateOrCreate(
                ['user_id' => $user->getKey(), 'address_type' => 'domicile'],
                ['administrative_area_id' => $district->getKey(), 'address_line' => 'Alamat fiktif khusus pengembangan', 'is_primary' => true, 'deleted_at' => null]
            );
            foreach ($data['interests'] as $slug) {
                $interest = Interest::query()->where('slug', $slug)->firstOrFail();
                UserInterest::withTrashed()->updateOrCreate(
                    ['user_id' => $user->getKey(), 'interest_id' => $interest->getKey()],
                    ['deleted_at' => null]
                );
            }
        }
    }
}
