<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\ActivityParticipation;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationMembership;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ActivityRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_open_mode_accepts_youth_and_duplicate_registration_is_blocked(): void
    {
        [$community, $manager, $activity] = $this->context('open');
        $youth = $this->youth();
        $this->actingAs($youth)->get(route('activities.show', $activity))->assertOk()->assertSee('Daftar Activity');
        $this->actingAs($youth)->post(route('activities.register', $activity), ['registration_notes' => 'Ingin belajar'])->assertRedirect();
        $participation = ActivityParticipation::firstOrFail();
        $this->assertSame(ActivityParticipation::REGISTRATION_ACCEPTED, $participation->registration_status);
        $this->assertSame(ActivityParticipation::COMPLETION_PENDING, $participation->completion_status);
        $this->assertSame(16, strlen($participation->getKey()));
        $this->actingAs($youth)->get(route('activities.show', $activity))->assertOk()->assertSee('Diterima');
        $this->assertSame(1, $youth->siporaNotifications()->count());
        $this->actingAs($youth)->post(route('activities.register', $activity))->assertSessionHasErrors('registration');
        $this->assertDatabaseCount('activity_participations', 1);
    }

    public function test_approval_required_mode_starts_pending_and_manager_can_accept(): void
    {
        [$community, $manager, $activity] = $this->context('approval', ['registration_mode' => 'approval_required']);
        $youth = $this->youth();
        $this->actingAs($youth)->post(route('activities.register', $activity))->assertRedirect();
        $participation = ActivityParticipation::firstOrFail();
        $this->assertSame(ActivityParticipation::REGISTRATION_PENDING, $participation->registration_status);
        $this->actingAs($manager)->get(route('manager.activities.participants.index', [$community, $activity]))->assertOk()->assertSee($youth->name);
        $this->actingAs($manager)->post(route('manager.activities.participants.review', [$community, $activity, $participation]), ['decision' => 'accepted'])->assertRedirect();
        $this->assertSame(ActivityParticipation::REGISTRATION_ACCEPTED, $participation->fresh()->registration_status);
        $this->assertTrue($participation->fresh()->reviewer->is($manager));
        $this->assertSame(1, $youth->siporaNotifications()->count());
    }

    public function test_manager_can_reject_pending_registration_with_reason(): void
    {
        [$community, $manager, $activity] = $this->context('reject', ['registration_mode' => 'approval_required']);
        $youth = $this->youth();
        $participation = $this->participation($activity, $youth);
        $route = route('manager.activities.participants.review', [$community, $activity, $participation]);
        $this->actingAs($manager)->post($route, ['decision' => 'rejected'])->assertSessionHasErrors('notes');
        $this->actingAs($manager)->post($route, ['decision' => 'rejected', 'notes' => 'Persyaratan belum terpenuhi'])->assertRedirect();
        $this->assertSame(ActivityParticipation::REGISTRATION_REJECTED, $participation->fresh()->registration_status);
        $this->assertSame(1, $youth->siporaNotifications()->count());
    }

    public function test_outsider_member_and_other_community_manager_cannot_manage_participants(): void
    {
        [$community, $manager, $activity] = $this->context('protected', ['registration_mode' => 'approval_required']);
        [$otherCommunity, $otherManager] = $this->context('other');
        $member = $this->youth();
        $this->membership($community, $member, OrganizationMembership::ROLE_MEMBER, $manager);
        $participation = $this->participation($activity, $this->youth());
        $route = route('manager.activities.participants.review', [$community, $activity, $participation]);
        $this->actingAs($otherManager)->post($route, ['decision' => 'accepted'])->assertForbidden();
        $this->actingAs($member)->post($route, ['decision' => 'accepted'])->assertForbidden();
        $this->actingAs($manager)->get(route('manager.activities.participants.index', [$otherCommunity, $activity]))->assertNotFound();
    }

    public function test_unpublished_closed_and_non_scheduled_activity_reject_registration(): void
    {
        $youth = $this->youth();
        [$community, $manager, $unpublished] = $this->context('unpublished', ['publication_status' => Activity::PUBLICATION_UNPUBLISHED, 'published_at' => null]);
        $this->actingAs($youth)->post(route('activities.register', $unpublished))->assertSessionHasErrors('registration');
        [$closedCommunity, $closedManager, $closed] = $this->context('closed', ['registration_close_at' => now()->subMinute()]);
        $this->actingAs($youth)->post(route('activities.register', $closed))->assertSessionHasErrors('registration');
        [$doneCommunity, $doneManager, $done] = $this->context('done', ['execution_status' => 'completed']);
        $this->actingAs($youth)->post(route('activities.register', $done))->assertSessionHasErrors('registration');
        $this->assertDatabaseCount('activity_participations', 0);
    }

    public function test_accepted_capacity_is_enforced_for_open_and_manager_approval(): void
    {
        [$community, $manager, $open] = $this->context('capacity-open', ['quota' => 1]);
        $first = $this->youth();
        $second = $this->youth();
        $this->actingAs($first)->post(route('activities.register', $open))->assertRedirect();
        $this->actingAs($second)->post(route('activities.register', $open))->assertSessionHasErrors('registration');

        [$approvalCommunity, $approvalManager, $approval] = $this->context('capacity-approval', ['quota' => 1, 'registration_mode' => 'approval_required']);
        $accepted = $this->participation($approval, $this->youth(), ActivityParticipation::REGISTRATION_ACCEPTED);
        $pending = $this->participation($approval, $this->youth());
        $this->actingAs($approvalManager)->post(route('manager.activities.participants.review', [$approvalCommunity, $approval, $pending]), ['decision' => 'accepted'])->assertSessionHasErrors('registration');
        $this->assertSame(ActivityParticipation::REGISTRATION_PENDING, $pending->fresh()->registration_status);
    }

    public function test_youth_can_cancel_before_deadline_but_not_after_cutoff(): void
    {
        [$community, $manager, $activity] = $this->context('cancel');
        $youth = $this->youth();
        $participation = $this->participation($activity, $youth, ActivityParticipation::REGISTRATION_ACCEPTED);
        $this->actingAs($youth)->delete(route('activities.registration.destroy', $activity))->assertRedirect();
        $this->assertSame(ActivityParticipation::REGISTRATION_CANCELLED, $participation->fresh()->registration_status);

        [$lateCommunity, $lateManager, $late] = $this->context('late-cancel', ['registration_close_at' => now()->subMinute()]);
        $lateYouth = $this->youth();
        $lateParticipation = $this->participation($late, $lateYouth, ActivityParticipation::REGISTRATION_ACCEPTED);
        $this->actingAs($lateYouth)->delete(route('activities.registration.destroy', $late))->assertSessionHasErrors('registration');
        $this->assertSame(ActivityParticipation::REGISTRATION_ACCEPTED, $lateParticipation->fresh()->registration_status);
    }

    public function test_onboarded_youth_still_needs_membership_and_age_eligibility(): void
    {
        [$community, $manager, $activity] = $this->context('eligibility', ['requires_identity_verification' => true, 'members_only' => true, 'min_age' => 18, 'max_age' => 30]);
        $youth = $this->youth();
        $youth->profile->update(['birth_date' => now()->subYears(17)->toDateString()]);
        $this->actingAs($youth)->post(route('activities.register', $activity))->assertSessionHasErrors('registration');
        $this->membership($community, $youth, OrganizationMembership::ROLE_MEMBER, $manager);
        $this->actingAs($youth)->post(route('activities.register', $activity))->assertSessionHasErrors('registration');
        $youth->profile->update(['birth_date' => now()->subYears(20)->toDateString()]);
        $this->actingAs($youth)->post(route('activities.register', $activity))->assertRedirect();
        $this->assertSame(ActivityParticipation::REGISTRATION_ACCEPTED, ActivityParticipation::firstOrFail()->registration_status);
    }

    public function test_participation_table_exists(): void
    {
        $this->assertTrue(Schema::hasTable('activity_participations'));
    }

    private function context(string $slug, array $override = []): array
    {
        $manager = $this->youth();
        $organizationCategory = OrganizationCategory::create(['name' => 'Kategori '.$slug, 'slug' => 'organization-'.$slug]);
        $community = Organization::create(['created_by_user_id' => $manager->getKey(), 'category_id' => $organizationCategory->getKey(), 'name' => 'Komunitas '.$slug,
            'slug' => 'community-'.$slug, 'description' => 'Komunitas aktif', 'social_links' => (object) [], 'review_status' => Organization::REVIEW_APPROVED,
            'operational_status' => Organization::OPERATIONAL_ACTIVE, 'approved_at' => now()]);
        $this->membership($community, $manager, OrganizationMembership::ROLE_LEADER, $manager);
        $category = ActivityCategory::firstOrCreate(['slug' => 'registration-category'], ['name' => 'Kategori Pendaftaran']);
        $data = array_merge(['organization_id' => $community->getKey(), 'category_id' => $category->getKey(), 'created_by_user_id' => $manager->getKey(),
            'title' => 'Activity '.$slug, 'slug' => 'activity-'.$slug, 'description' => 'Activity publik.', 'location_type' => 'offline', 'venue_name' => 'Gedung Pemuda',
            'start_at' => now()->addDays(5), 'end_at' => now()->addDays(5)->addHours(2), 'registration_open_at' => now()->subDay(),
            'registration_close_at' => now()->addDays(3), 'quota' => 20, 'registration_mode' => 'open', 'review_status' => Activity::REVIEW_APPROVED,
            'publication_status' => Activity::PUBLICATION_PUBLISHED, 'execution_status' => Activity::EXECUTION_SCHEDULED, 'published_at' => now()], $override);

        return [$community, $manager, Activity::create($data)];
    }

    private function youth(): User
    {
        $user = User::factory()->create();
        $user->assignRole('youth');

        return $this->completeYouthOnboarding($user);
    }

    private function participation(Activity $activity, User $user, string $status = ActivityParticipation::REGISTRATION_PENDING): ActivityParticipation
    {
        return ActivityParticipation::create(['activity_id' => $activity->getKey(), 'user_id' => $user->getKey(), 'activity_role' => 'participant',
            'registration_status' => $status, 'completion_status' => ActivityParticipation::COMPLETION_PENDING, 'requested_at' => now()]);
    }

    private function membership(Organization $community, User $user, string $role, User $approver): void
    {
        OrganizationMembership::create(['organization_id' => $community->getKey(), 'user_id' => $user->getKey(), 'access_role' => $role,
            'membership_status' => OrganizationMembership::STATUS_ACTIVE, 'requested_at' => now(), 'approved_at' => now(), 'approved_by' => $approver->getKey()]);
    }
}
