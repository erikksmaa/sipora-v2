<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationMembership;
use App\Models\OrganizationVerificationRequest;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommunityManagerWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_leader_and_manager_can_access_dashboard_but_member_and_outsider_cannot(): void
    {
        [$community, $leader] = $this->community('akses');
        $manager = $this->youth();
        $member = $this->youth();
        $outsider = $this->youth();
        $this->membership($community, $manager, OrganizationMembership::ROLE_MANAGER, $leader);
        $this->membership($community, $member, OrganizationMembership::ROLE_MEMBER, $leader);

        $this->actingAs($leader)->get(route('manager.dashboard', $community))->assertOk()->assertSee('Operasional Community');
        $this->actingAs($manager)->get(route('manager.dashboard', $community))->assertOk();
        $this->actingAs($member)->get(route('manager.dashboard', $community))->assertForbidden();
        $this->actingAs($outsider)->get(route('manager.dashboard', $community))->assertForbidden();

        foreach (['admin', 'verifier'] as $role) {
            $governmentUser = User::factory()->create();
            $governmentUser->assignRole($role);
            $this->actingAs($governmentUser)->get(route('manager.dashboard', $community))->assertForbidden();
        }
    }

    public function test_manager_in_community_a_cannot_access_community_b(): void
    {
        [$communityA, $leaderA] = $this->community('komunitas-a');
        [$communityB] = $this->community('komunitas-b');
        $managerA = $this->youth();
        $this->membership($communityA, $managerA, OrganizationMembership::ROLE_MANAGER, $leaderA);

        $this->actingAs($managerA)->get(route('manager.dashboard', $communityB))->assertForbidden();
        $this->actingAs($managerA)->get(route('manager.profile.edit', $communityB))->assertForbidden();
    }

    public function test_manager_can_update_public_profile_without_changing_protected_fields(): void
    {
        [$community, $leader] = $this->community('profil');
        $manager = $this->youth();
        $this->membership($community, $manager, OrganizationMembership::ROLE_MANAGER, $leader);
        $originalCategory = $community->category_id;
        $originalOwner = $community->created_by_user_id;

        $this->actingAs($manager)->patch(route('manager.profile.update', $community), [
            'name' => 'Komunitas Profil Diperbarui',
            'description' => 'Deskripsi publik baru.',
            'contact_email' => 'publik@example.test',
            'contact_phone' => '+628123456789',
            'website_url' => 'https://komunitas.example.test',
            'address_text' => 'Alamat publik komunitas',
            'social_instagram' => 'https://instagram.com/komunitas',
            'review_status' => 'rejected',
            'operational_status' => 'suspended',
            'created_by_user_id' => $manager->uuid(),
            'category_id' => '018f7890-1234-7abc-8def-0123456789ab',
        ])->assertRedirect(route('manager.profile.edit', $community));

        $community->refresh();
        $this->assertSame('Komunitas Profil Diperbarui', $community->name);
        $this->assertSame('publik@example.test', $community->contact_email);
        $this->assertSame(Organization::REVIEW_APPROVED, $community->review_status);
        $this->assertSame(Organization::OPERATIONAL_ACTIVE, $community->operational_status);
        $this->assertSame($originalCategory, $community->category_id);
        $this->assertSame($originalOwner, $community->created_by_user_id);
    }

    public function test_public_community_page_renders_safe_data_without_internal_review_details(): void
    {
        [$community, $leader] = $this->community('publik');
        $community->forceFill(['contact_email' => 'contact@example.test'])->save();
        OrganizationVerificationRequest::create([
            'organization_id' => $community->getKey(),
            'submitted_by' => $leader->getKey(),
            'status' => OrganizationVerificationRequest::STATUS_APPROVED,
            'submitted_at' => now()->subDay(),
            'reviewed_at' => now(),
            'review_notes' => 'INTERNAL-REVIEW-SECRET',
        ]);

        $this->get(route('communities.show', $community))
            ->assertOk()
            ->assertSee($community->name)
            ->assertSee('contact@example.test')
            ->assertSee('Anggota aktif')
            ->assertSee('Belum ada Activity')
            ->assertDontSee('INTERNAL-REVIEW-SECRET')
            ->assertDontSee($leader->email);
    }

    public function test_multi_community_switcher_lists_only_manageable_communities(): void
    {
        [$communityA, $leaderA] = $this->community('managed-a');
        [$communityB, $leaderB] = $this->community('managed-b');
        [$communityC, $leaderC] = $this->community('member-only');
        $manager = $this->youth();
        $this->membership($communityA, $manager, OrganizationMembership::ROLE_MANAGER, $leaderA);
        $this->membership($communityB, $manager, OrganizationMembership::ROLE_MANAGER, $leaderB);
        $this->membership($communityC, $manager, OrganizationMembership::ROLE_MEMBER, $leaderC);

        $this->actingAs($manager)->get(route('manager.dashboard', $communityA))
            ->assertOk()
            ->assertSee('Ganti Community')
            ->assertSee($communityA->name)
            ->assertSee($communityB->name)
            ->assertDontSee($communityC->name);
    }

    private function youth(): User
    {
        $user = User::factory()->create();
        $user->assignRole('youth');

        return $user;
    }

    private function community(string $slug): array
    {
        $leader = $this->youth();
        $category = OrganizationCategory::create(['name' => 'Kategori '.$slug, 'slug' => 'kategori-'.$slug]);
        $community = Organization::create([
            'created_by_user_id' => $leader->getKey(),
            'category_id' => $category->getKey(),
            'name' => 'Komunitas '.$slug,
            'slug' => $slug,
            'description' => 'Deskripsi komunitas publik.',
            'social_links' => (object) [],
            'review_status' => Organization::REVIEW_APPROVED,
            'operational_status' => Organization::OPERATIONAL_ACTIVE,
            'approved_at' => now(),
        ]);
        $this->membership($community, $leader, OrganizationMembership::ROLE_LEADER, $leader);

        return [$community, $leader];
    }

    private function membership(Organization $community, User $user, string $role, User $approver): OrganizationMembership
    {
        return OrganizationMembership::create([
            'organization_id' => $community->getKey(),
            'user_id' => $user->getKey(),
            'access_role' => $role,
            'membership_status' => OrganizationMembership::STATUS_ACTIVE,
            'requested_at' => now(),
            'approved_at' => now(),
            'approved_by' => $approver->getKey(),
        ]);
    }
}
