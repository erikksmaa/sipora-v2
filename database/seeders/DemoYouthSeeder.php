<?php

namespace Database\Seeders;

use App\Models\AdministrativeArea;
use App\Models\Interest;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserIdentity;
use App\Models\UserInterest;
use App\Models\UserProfile;
use App\Models\UserProfileVisibility;
use App\Models\UserSkill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Support\Demo\DemoAssetManifest;

class DemoYouthSeeder extends Seeder
{
    public static function users()
    {
        return User::where('email', 'youth1@sipora.test')->orWhere('email', 'like', 'demo.youth%@sipora.test')->orderByRaw("CASE WHEN email = 'youth1@sipora.test' THEN 0 ELSE 1 END")->orderBy('email')->get();
    }

    public function run(): void
    {
        $names = ['Erik Kusuma Rais', 'Aditya Pratama', 'Nabila Safitri', 'Fajar Maulana', 'Alya Putri', 'Bagas Ramadhan', 'Dinda Maharani', 'Rizky Firmansyah', 'Siti Aulia', 'Dimas Nugroho', 'Nadia Kurniawati', 'Raka Saputra', 'Anisa Rahma', 'Galih Prakoso', 'Intan Permata', 'Farhan Hakim', 'Laila Zahra', 'Yoga Wibowo', 'Maya Salsabila', 'Bima Setiawan', 'Putri Amelia', 'Arif Hidayat', 'Ratna Dewi', 'Tegar Santoso'];
        $areas = AdministrativeArea::where('area_level', 'district')->orderBy('code')->get();
        $interests = Interest::orderBy('slug')->get();
        $skills = Skill::orderBy('slug')->get();
        $admin = User::where('email', 'admin@sipora.test')->firstOrFail();
        foreach ($names as $i => $name) {
            $email = $i === 0 ? 'youth1@sipora.test' : sprintf('demo.youth%02d@sipora.test', $i);
            $user = User::firstOrCreate(['email' => $email], ['name' => $name, 'password' => Hash::make(UserSeeder::PASSWORD)]);
            $date = now()->startOfMonth()->subMonths(3 + $i * 2)->addDays($i % 20);
            $user->forceFill(['name' => $name, 'email_verified_at' => $date, 'created_at' => $date])->save();
            $user->syncRoles(['youth']);
            UserProfile::withTrashed()->updateOrCreate(['user_id' => $user->getKey()], [
                'public_slug' => $i === 0 ? 'erik-kusuma-rais' : sprintf('demo-pemuda-%02d', $i), 'full_name' => $name,
                'birth_place' => 'Pemalang', 'birth_date' => sprintf('%d-%02d-12', now()->year - 17 - ($i % 13), 1 + $i % 12),
                'gender' => $i % 2 ? 'female' : 'male', 'bio' => $i > 20 ? null : 'Belajar bersama komunitas, membangun karya, dan berkontribusi untuk Pemalang.',
                'occupation_status' => $i > 20 ? null : ($i % 3 ? 'student' : 'university_student'), 'deleted_at' => null,
            ]);
            UserAddress::withTrashed()->updateOrCreate(['user_id' => $user->getKey(), 'address_type' => 'domicile'], [
                'administrative_area_id' => $areas[$i % $areas->count()]->getKey(), 'address_line' => 'Alamat fiktif demo; bukan alamat pribadi', 'is_primary' => true, 'deleted_at' => null,
            ]);
            UserProfileVisibility::withTrashed()->updateOrCreate(['user_id' => $user->getKey()], [
                'is_profile_public' => $i % 6 !== 5, 'show_bio' => true, 'show_interests' => true, 'show_skills' => true,
                'show_activity_passport' => true, 'show_certificates' => true, 'deleted_at' => null,
            ]);
            // No identity number or document is fabricated. Verified state is a development fixture.
            UserIdentity::withTrashed()->updateOrCreate(['user_id' => $user->getKey()], [
                'verification_status' => $i < 20 ? 'verified' : 'unverified', 'verification_method' => $i < 20 ? 'ktp' : null,
                'verified_at' => $i < 20 ? $date->copy()->addDay() : null, 'verified_by' => $i < 20 ? $admin->getKey() : null, 'deleted_at' => null,
            ]);
            if ($i < 22) {
                foreach ([$interests[$i % $interests->count()], $interests[($i + 3) % $interests->count()]] as $interest) {
                    UserInterest::withTrashed()->updateOrCreate(['user_id' => $user->getKey(), 'interest_id' => $interest->getKey()], ['deleted_at' => null]);
                }
                foreach ([$skills[$i % $skills->count()], $skills[($i + 4) % $skills->count()]] as $skill) {
                    UserSkill::withTrashed()->updateOrCreate(['user_id' => $user->getKey(), 'skill_id' => $skill->getKey()], ['proficiency_level' => $i % 3 ? 'beginner' : 'intermediate', 'is_self_reported' => true, 'deleted_at' => null]);
                }
            }
        }
        $erik = User::where('email', 'youth1@sipora.test')->firstOrFail();
        foreach (['teknologi', 'seni-kreatif'] as $slug) {
            UserInterest::withTrashed()->updateOrCreate(['user_id' => $erik->getKey(), 'interest_id' => Interest::where('slug', $slug)->firstOrFail()->getKey()], ['deleted_at' => null]);
        }
        foreach (['pemrograman-web-software', 'public-speaking-komunikasi'] as $slug) {
            UserSkill::withTrashed()->updateOrCreate(['user_id' => $erik->getKey(), 'skill_id' => Skill::where('slug', $slug)->firstOrFail()->getKey()], ['proficiency_level' => 'intermediate', 'is_self_reported' => true, 'deleted_at' => null]);
        }
        if (DemoAssetManifest::enabled()) {
            $profiles = json_decode(file_get_contents(public_path('media/demo/youth/manifest.json')), true, flags: JSON_THROW_ON_ERROR);
            foreach (DemoAssetManifest::data()['youth'] as $id => $entry) {
                $detail = collect($profiles['profiles'])->firstWhere('id', $id);
                $email = $id === 'Y001' ? 'youth1@sipora.test' : 'asset.'.strtolower($id).'@sipora.test';
                $person = User::where('name', $entry['name'])->where('email', 'like', '%@sipora.test')->first()
                    ?? User::firstOrCreate(['email' => $email], ['name' => $entry['name'], 'password' => Hash::make(UserSeeder::PASSWORD)]);
                if ($person->name !== $entry['name']) throw new \RuntimeException('Canonical Youth identity mismatch: '.$id);
                $person->assignRole('youth');
                if (! $person->email_verified_at) $person->forceFill(['email_verified_at' => now()])->save();
                $profile = UserProfile::firstOrCreate(['user_id' => $person->getKey()], [
                    'public_slug' => 'sipora-demo-'.\Illuminate\Support\Str::slug($entry['name']), 'full_name' => $entry['name'],
                    'birth_place' => 'Pemalang', 'birth_date' => (now()->year - $detail['age']).'-01-12',
                    'gender' => $detail['gender'] === 'man' ? 'male' : 'female', 'occupation_status' => 'student',
                    'bio' => 'Karakter fiktif DATA DEMO SIPORA.',
                ]);
                $values = ['full_name' => $entry['name'], 'gender' => $detail['gender'] === 'man' ? 'male' : 'female',
                    'profile_photo_path' => DemoAssetManifest::store($entry['profile'], 'public')];
                if ($profile->birth_date?->year !== 2026 - $detail['age']) $values['birth_date'] = (2026 - $detail['age']).'-01-12';
                $profile->forceFill($values)->save();
                UserAddress::firstOrCreate(['user_id' => $person->getKey(), 'address_type' => 'domicile'], [
                    'administrative_area_id' => $areas[(int) substr($id, 1) % $areas->count()]->getKey(),
                    'address_line' => 'Alamat fiktif demo', 'is_primary' => true,
                ]);
                $visibility = UserProfileVisibility::firstOrCreate(['user_id' => $person->getKey()], [
                    'is_profile_public' => (int) substr($id, 1) % 6 !== 0, 'show_bio' => true,
                    'show_interests' => true, 'show_skills' => true,
                ]);
                $visibility->forceFill(['show_photo' => true])->save();
                UserIdentity::firstOrCreate(['user_id' => $person->getKey()], ['verification_status' => 'unverified']);
            }
        }
    }
}
