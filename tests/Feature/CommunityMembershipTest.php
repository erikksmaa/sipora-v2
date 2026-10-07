<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationMembership;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CommunityMembershipTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_youth_can_request_active_community_and_duplicate_or_existing_membership_is_blocked(): void
    {
        [$community] = $this->activeCommunity();
        $youth = $this->youth();

        $this->actingAs($youth)->get(route('communities.show', $community))->assertOk()->assertSee('Gabung Komunitas');
        $this->actingAs($youth)->post(route('communities.join', $community))->assertRedirect(route('communities.show', $community));
        $membership = $youth->organizationMemberships()->firstOrFail();
        $this->assertSame(OrganizationMembership::STATUS_PENDING, $membership->membership_status);
        $this->assertSame(OrganizationMembership::ROLE_MEMBER, $membership->access_role);

        $this->actingAs($youth)->post(route('communities.join', $community))->assertSessionHasErrors('membership');
        $membership->forceFill(['membership_status' => OrganizationMembership::STATUS_ACTIVE, 'approved_at' => now()])->save();
        $this->actingAs($youth)->post(route('communities.join', $community))->assertSessionHasErrors('membership');
    }

    public function test_joining_inactive_community_is_blocked(): void
    {
        [$community] = $this->activeCommunity();
        $community->operational_status = Organization::OPERATIONAL_INACTIVE;
        $community->save();

        $this->actingAs($this->youth())->post(route('communities.join', $community))->assertSessionHasErrors('membership');
        $this->assertDatabaseCount('organization_memberships', 1);
    }

    public function test_leader_can_accept_and_active_member_is_created_and_notified(): void
    {
        [$community, $leader] = $this->activeCommunity();
        $applicant = $this->youth();
        $request = $this->pendingRequest($community, $applicant);

        $this->actingAs($leader)->get(route('manager.members.index', $community))->assertOk()->assertSee($applicant->email);
        $this->actingAs($leader)->post(route('manager.join-requests.review', [$community, $request]), [
            'decision' => 'accepted',
        ])->assertRedirect(route('manager.members.index', $community));

        $request->refresh();
        $this->assertSame(OrganizationMembership::STATUS_ACTIVE, $request->membership_status);
        $this->assertSame(OrganizationMembership::ROLE_MEMBER, $request->access_role);
        $this->assertTrue($request->approver->is($leader));
        $this->assertDatabaseHas('notifications', [
            'user_id' => $applicant->getKey(), 'notification_type' => 'community_membership_result',
        ]);
    }

    public function test_manager_can_reject_a_join_request(): void
    {
        [$community, $leader] = $this->activeCommunity();
        $manager = $this->youth();
        $this->membership($community, $manager, OrganizationMembership::ROLE_MANAGER, $leader);
        $applicant = $this->youth();
        $request = $this->pendingRequest($community, $applicant);

        $this->actingAs($manager)->post(route('manager.join-requests.review', [$community, $request]), [
            'decision' => 'rejected',
        ])->assertRedirect();

        $this->assertSame(OrganizationMembership::STATUS_REJECTED, $request->fresh()->membership_status);
        $this->assertSame(1, $applicant->siporaNotifications()->count());
    }

    public function test_outsider_and_manager_of_another_community_cannot_manage_requests(): void
    {
        [$communityA, $leaderA] = $this->activeCommunity('komunitas-a');
        [$communityB] = $this->activeCommunity('komunitas-b');
        $managerA = $this->youth();
        $this->membership($communityA, $managerA, OrganizationMembership::ROLE_MANAGER, $leaderA);
        $requestB = $this->pendingRequest($communityB, $this->youth());

        $this->actingAs($this->youth())->get(route('manager.members.index', $communityB))->assertForbidden();
        $this->actingAs($managerA)->get(route('manager.members.index', $communityB))->assertForbidden();
        $this->actingAs($managerA)->post(route('manager.join-requests.review', [$communityB, $requestB]), ['decision' => 'accepted'])->assertForbidden();
        $this->assertSame(OrganizationMembership::STATUS_PENDING, $requestB->fresh()->membership_status);
    }

    public function test_member_can_leave_but_last_leader_cannot_leave_or_remove_themselves(): void
    {
        [$community, $leader] = $this->activeCommunity();
        $member = $this->youth();
        $membership = $this->membership($community, $member, OrganizationMembership::ROLE_MEMBER, $leader);

        $this->actingAs($member)->delete(route('communities.membership.destroy', $community))->assertRedirect();
        $this->assertSame(OrganizationMembership::STATUS_LEFT, $membership->fresh()->membership_status);

        $this->actingAs($leader)->delete(route('communities.membership.destroy', $community))->assertSessionHasErrors('membership');
        $leaderMembership = $leader->organizationMemberships()->where('organization_id', $community->getKey())->firstOrFail();
        $this->actingAs($leader)->delete(route('manager.members.destroy', [$community, $leaderMembership]))->assertForbidden();
        $this->assertSame(OrganizationMembership::STATUS_ACTIVE, $leaderMembership->fresh()->membership_status);
    }

    public function test_member_cannot_self_promote_and_contextual_roles_are_not_global_roles(): void
    {
        [$community, $leader] = $this->activeCommunity();
        $member = $this->youth();
        $membership = $this->membership($community, $member, OrganizationMembership::ROLE_MEMBER, $leader);

        $this->actingAs($member)->patch(route('manager.members.role.update', [$community, $membership]), [
            'access_role' => OrganizationMembership::ROLE_MANAGER,
        ])->assertForbidden();

        $this->assertSame(OrganizationMembership::ROLE_MEMBER, $membership->fresh()->access_role);
        $this->assertFalse(Role::whereIn('name', ['leader', 'manager', 'member'])->exists());
    }

    private function youth(): User
    {
        $user = User::factory()->create();
        $user->assignRole('youth');

        return $this->completeYouthOnboarding($user);
    }

    private function activeCommunity(string $slug = 'komunitas-aktif'): array
    {
        $leader = $this->youth();
        $category = OrganizationCategory::create(['name' => 'Kategori Uji '.$slug, 'slug' => 'kategori-'.$slug]);
        $community = Organization::create([
            'created_by_user_id' => $leader->getKey(),
            'category_id' => $category->getKey(),
            'name' => 'Komunitas '.ucfirst($slug),
            'slug' => $slug,
            'description' => 'Komunitas aktif untuk pengujian.',
            'social_links' => (object) [],
            'review_status' => Organization::REVIEW_APPROVED,
            'operational_status' => Organization::OPERATIONAL_ACTIVE,
            'approved_at' => now(),
        ]);
        $this->membership($community, $leader, OrganizationMembership::ROLE_LEADER, $leader);

        return [$community, $leader];
    }

    private function pendingRequest(Organization $community, User $applicant): OrganizationMembership
    {
        return OrganizationMembership::create([
            'organization_id' => $community->getKey(),
            'user_id' => $applicant->getKey(),
            'access_role' => OrganizationMembership::ROLE_MEMBER,
            'membership_status' => OrganizationMembership::STATUS_PENDING,
            'requested_at' => now(),
        ]);
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
