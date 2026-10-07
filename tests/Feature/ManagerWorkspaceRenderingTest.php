<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationMembership;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagerWorkspaceRenderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_dashboard_uses_shared_shell_and_only_manageable_community_switcher(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $leader = User::factory()->create();
        $leader->assignRole('youth');
        $this->completeYouthOnboarding($leader);
        $category = OrganizationCategory::create(['name' => 'Komunitas Uji', 'slug' => 'komunitas-uji']);

        $first = $this->community('community-satu', $category, $leader);
        $second = $this->community('community-dua', $category, $leader);
        $outsider = User::factory()->create();
        $outsider->assignRole('youth');
        $this->completeYouthOnboarding($outsider);
        $other = $this->community('community-lain', $category, $outsider);

        $response = $this->actingAs($leader)->get(route('manager.dashboard', $first));

        $response->assertOk()->assertSee('Navigasi Community')->assertSee('Antrean operasional')
            ->assertSee('Ganti Community')->assertSee($second->name)->assertDontSee($other->name)
            ->assertSee('Workspace Community')->assertSee('Jelajahi SIPORA');
        $this->actingAs($leader)->get(route('manager.dashboard', $other))->assertForbidden();
    }

    public function test_manager_members_search_keeps_context_and_finds_active_member(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $leader = User::factory()->create();
        $leader->assignRole('youth');
        $this->completeYouthOnboarding($leader);
        $category = OrganizationCategory::create(['name' => 'Komunitas Uji', 'slug' => 'komunitas-uji']);
        $organization = $this->community('community-satu', $category, $leader);
        $member = User::factory()->create(['name' => 'Nama Unik Anggota']);
        $member->assignRole('youth');
        OrganizationMembership::create([
            'organization_id' => $organization->getKey(),
            'user_id' => $member->getKey(),
            'access_role' => OrganizationMembership::ROLE_MEMBER,
            'membership_status' => OrganizationMembership::STATUS_ACTIVE,
            'requested_at' => now(),
            'approved_at' => now(),
            'approved_by' => $leader->getKey(),
        ]);

        $this->actingAs($leader)->get(route('manager.members.index', $organization).'?q=Nama+Unik')
            ->assertOk()->assertSee('Nama Unik Anggota')->assertSee('Cari anggota');
    }

    private function community(string $slug, OrganizationCategory $category, User $leader): Organization
    {
        $community = Organization::create([
            'created_by_user_id' => $leader->getKey(),
            'category_id' => $category->getKey(),
            'name' => 'Community '.$slug,
            'slug' => $slug,
            'description' => 'Community aktif.',
            'social_links' => (object) [],
            'review_status' => Organization::REVIEW_APPROVED,
            'operational_status' => Organization::OPERATIONAL_ACTIVE,
            'approved_at' => now(),
        ]);
        OrganizationMembership::create([
            'organization_id' => $community->getKey(),
            'user_id' => $leader->getKey(),
            'access_role' => OrganizationMembership::ROLE_LEADER,
            'membership_status' => OrganizationMembership::STATUS_ACTIVE,
            'requested_at' => now(),
            'approved_at' => now(),
            'approved_by' => $leader->getKey(),
        ]);

        return $community;
    }
}
