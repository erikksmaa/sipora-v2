<?php

namespace Tests\Feature;

use App\Models\Skill;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserProfileVisibility;
use App\Support\BinaryUuid;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicYouthDirectoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_only_public_portfolios_appear_and_link_to_existing_portfolio(): void
    {
        $public = $this->youth('pemuda-publik', 'Pemuda Publik');
        $this->youth('pemuda-privat', 'Pemuda Privat', ['is_profile_public' => false]);

        $this->get(route('youth-directory.index'))->assertOk()
            ->assertSee('Pemuda Publik')->assertDontSee('Pemuda Privat')
            ->assertSee(route('portfolio.show', $public->profile->public_slug), false);
        $this->get(route('portfolio.show', 'pemuda-privat'))->assertNotFound();
    }

    public function test_private_fields_and_sensitive_account_data_never_leak(): void
    {
        $user = $this->youth('aman-publik', 'Nama Aman', ['show_bio' => false, 'show_skills' => false, 'show_photo' => false], [
            'email' => 'rahasia@example.test',
        ]);
        $user->profile->update(['bio' => 'Bio Sangat Rahasia', 'phone' => '081234567890', 'profile_photo_path' => 'profile-photos/private.jpg']);
        $skill = Skill::create(['name' => 'Skill Sangat Rahasia', 'slug' => 'skill-sangat-rahasia']);
        $user->skills()->attach($skill->getKey(), ['id' => BinaryUuid::generate(), 'is_self_reported' => true]);

        $response = $this->get(route('youth-directory.index'))->assertOk()->assertSee('Nama Aman');
        foreach (['rahasia@example.test', '081234567890', 'Bio Sangat Rahasia', 'Skill Sangat Rahasia', 'private.jpg', 'NIK', 'identity_status'] as $secret) {
            $response->assertDontSee($secret);
        }
        $this->get(route('youth-directory.index', ['q' => 'Sangat Rahasia']))->assertOk()->assertDontSee('Nama Aman');
        $this->get(route('search.index', ['q' => 'Sangat Rahasia']))->assertOk()->assertDontSee('Nama Aman');
    }

    public function test_search_is_public_safe_and_results_are_paginated_neutrally(): void
    {
        foreach (range(1, 13) as $index) {
            $this->youth('pemuda-'.$index, sprintf('Pemuda %02d', $index));
        }

        $this->get(route('youth-directory.index', ['q' => 'Pemuda 13']))->assertOk()->assertSee('Pemuda 13')->assertDontSee('Pemuda 01');
        $page = $this->get(route('youth-directory.index'))->assertOk()->assertSee('13 profil publik');
        $page->assertSee('page=2', false)->assertSee('Urutan nama bersifat netral');
    }

    public function test_global_search_contains_only_public_youth_group(): void
    {
        $this->youth('cari-publik', 'Satria Pencarian');
        $this->youth('cari-privat', 'Satria Privat', ['is_profile_public' => false]);

        $this->get(route('search.index', ['q' => 'Satria']))->assertOk()
            ->assertSee('Pemuda')->assertSee('Satria Pencarian')->assertDontSee('Satria Privat');
    }

    private function youth(string $slug, string $name, array $visibility = [], array $attributes = []): User
    {
        $user = User::factory()->create($attributes + ['name' => $name]);
        $user->assignRole('youth');
        UserProfile::create([
            'user_id' => $user->getKey(), 'public_slug' => $slug, 'full_name' => $name,
            'birth_date' => '2002-01-01', 'bio' => 'Bio publik '.$name, 'occupation_title' => 'Pemuda Pemalang',
        ]);
        UserProfileVisibility::create($visibility + [
            'user_id' => $user->getKey(), 'is_profile_public' => true, 'show_photo' => true,
            'show_bio' => true, 'show_interests' => true, 'show_skills' => true, 'show_education' => true,
            'show_organization_experience' => true, 'show_community_membership' => true,
            'show_activity_passport' => true, 'show_certificates' => true, 'show_achievements' => true,
            'show_business_experience' => true,
        ]);

        return $user->fresh(['profile', 'profileVisibility']);
    }
}
