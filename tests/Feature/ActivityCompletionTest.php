<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityAttendance;
use App\Models\ActivityCategory;
use App\Models\ActivityParticipation;
use App\Models\ActivitySession;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationMembership;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityCompletionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_manager_can_complete_accepted_pending_participation_with_audit_and_notification(): void
    {
        [$community, $manager, $activity] = $this->context('complete', Activity::EXECUTION_COMPLETED, OrganizationMembership::ROLE_MANAGER);
        $youth = $this->youth();
        $participation = $this->participation($activity, $youth);
        $attendance = $this->attendance($activity, $participation, $manager, ActivityAttendance::STATUS_PRESENT);

        $this->actingAs($manager)->post($this->completionRoute($community, $activity, $participation), ['decision' => 'completed'])->assertRedirect();

        $participation->refresh();
        $this->assertSame(ActivityParticipation::COMPLETION_COMPLETED, $participation->completion_status);
        $this->assertNotNull($participation->completed_at);
        $this->assertSame(ActivityAttendance::STATUS_PRESENT, $attendance->fresh()->attendance_status);
        $this->assertDatabaseHas('activity_log', ['event' => 'participation_completed']);
        $notification = $youth->siporaNotifications()->firstOrFail();
        $this->assertSame('activity_participation_completion', $notification->notification_type);
        $this->assertStringNotContainsString($youth->email, json_encode($notification->data));
    }

    public function test_manager_can_mark_accepted_pending_participation_no_show_without_rewriting_attendance(): void
    {
        [$community, $manager, $activity] = $this->context('no-show');
        $youth = $this->youth();
        $participation = $this->participation($activity, $youth);
        $attendance = $this->attendance($activity, $participation, $manager, ActivityAttendance::STATUS_PRESENT);

        $this->actingAs($manager)->post($this->completionRoute($community, $activity, $participation), ['decision' => 'no_show'])->assertRedirect();

        $this->assertSame(ActivityParticipation::COMPLETION_NO_SHOW, $participation->fresh()->completion_status);
        $this->assertNull($participation->fresh()->completed_at);
        $this->assertSame(ActivityAttendance::STATUS_PRESENT, $attendance->fresh()->attendance_status);
        $this->assertDatabaseHas('activity_log', ['event' => 'participation_marked_no_show']);
        $this->assertSame(1, $youth->siporaNotifications()->count());
    }

    public function test_non_accepted_registration_states_cannot_be_completed(): void
    {
        foreach ([ActivityParticipation::REGISTRATION_PENDING, ActivityParticipation::REGISTRATION_REJECTED, ActivityParticipation::REGISTRATION_CANCELLED] as $index => $status) {
            [$community, $manager, $activity] = $this->context('blocked-'.$index);
            $participation = $this->participation($activity, $this->youth(), $status);
            $this->actingAs($manager)->post($this->completionRoute($community, $activity, $participation), ['decision' => 'completed'])
                ->assertSessionHasErrors('completion');
            $this->assertSame(ActivityParticipation::COMPLETION_PENDING, $participation->fresh()->completion_status);
        }
    }

    public function test_terminal_decisions_cannot_be_replayed_or_reversed(): void
    {
        foreach ([ActivityParticipation::COMPLETION_COMPLETED, ActivityParticipation::COMPLETION_NO_SHOW] as $index => $terminal) {
            [$community, $manager, $activity] = $this->context('terminal-'.$index);
            $participation = $this->participation($activity, $this->youth(), ActivityParticipation::REGISTRATION_ACCEPTED, $terminal);
            $this->actingAs($manager)->post($this->completionRoute($community, $activity, $participation), ['decision' => 'completed'])
                ->assertSessionHasErrors('completion');
            $this->assertSame($terminal, $participation->fresh()->completion_status);
        }
    }

    public function test_cross_community_manager_member_participant_admin_verifier_and_guest_are_denied(): void
    {
        [$community, $manager, $activity] = $this->context('authorized');
        [$otherCommunity, $otherManager] = $this->context('other');
        $participant = $this->youth();
        $member = $this->youth();
        $this->membership($community, $member, OrganizationMembership::ROLE_MEMBER, $manager);
        $participation = $this->participation($activity, $participant);
        $route = $this->completionRoute($community, $activity, $participation);
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $verifier = User::factory()->create();
        $verifier->assignRole('verifier');

        $this->post($route, ['decision' => 'completed'])->assertRedirectToRoute('login');
        foreach ([$otherManager, $member, $participant, $admin, $verifier] as $actor) {
            $this->actingAs($actor)->post($route, ['decision' => 'completed'])->assertForbidden();
        }
        $this->actingAs($manager)->post(route('manager.activities.participants.complete', [$otherCommunity, $activity, $participation]), ['decision' => 'completed'])->assertForbidden();
    }

    public function test_activity_must_be_completed_and_attendance_never_auto_completes(): void
    {
        [$community, $manager, $activity] = $this->context('gate', Activity::EXECUTION_ONGOING);
        $participation = $this->participation($activity, $this->youth());
        $this->attendance($activity, $participation, $manager, ActivityAttendance::STATUS_PRESENT);
        $this->assertSame(ActivityParticipation::COMPLETION_PENDING, $participation->fresh()->completion_status);

        $this->actingAs($manager)->post($this->completionRoute($community, $activity, $participation), ['decision' => 'completed'])
            ->assertSessionHasErrors('completion');
        $this->actingAs($manager)->post(route('manager.activities.complete-execution', [$community, $activity]))->assertRedirect();
        $this->assertSame(Activity::EXECUTION_COMPLETED, $activity->fresh()->execution_status);
    }

    private function completionRoute(Organization $community, Activity $activity, ActivityParticipation $participation): string
    {
        return route('manager.activities.participants.complete', [$community, $activity, $participation]);
    }

    private function context(string $slug, string $execution = Activity::EXECUTION_COMPLETED, string $managerRole = OrganizationMembership::ROLE_LEADER): array
    {
        $manager = $this->youth();
        $organizationCategory = OrganizationCategory::create(['name' => 'Kategori '.$slug, 'slug' => 'organization-'.$slug]);
        $community = Organization::create(['created_by_user_id' => $manager->getKey(), 'category_id' => $organizationCategory->getKey(), 'name' => 'Komunitas '.$slug,
            'slug' => 'community-'.$slug, 'description' => 'Komunitas aktif', 'social_links' => (object) [], 'review_status' => Organization::REVIEW_APPROVED,
            'operational_status' => Organization::OPERATIONAL_ACTIVE, 'approved_at' => now()]);
        $this->membership($community, $manager, $managerRole, $manager);
        $category = ActivityCategory::firstOrCreate(['slug' => 'completion-category'], ['name' => 'Kategori Penyelesaian']);
        $activity = Activity::create(['organization_id' => $community->getKey(), 'category_id' => $category->getKey(), 'created_by_user_id' => $manager->getKey(),
            'title' => 'Activity '.$slug, 'slug' => 'activity-'.$slug, 'description' => 'Activity selesai.', 'location_type' => 'offline', 'venue_name' => 'Gedung Pemuda',
            'start_at' => now()->subDays(2), 'end_at' => now()->subDay(), 'registration_mode' => 'open', 'review_status' => Activity::REVIEW_APPROVED,
            'publication_status' => Activity::PUBLICATION_PUBLISHED, 'execution_status' => $execution, 'published_at' => now()->subWeek()]);
        ActivitySession::create(['activity_id' => $activity->getKey(), 'session_number' => 1, 'title' => 'Sesi 1', 'start_at' => now()->subDays(2), 'end_at' => now()->subDays(2)->addHour()]);

        return [$community, $manager, $activity];
    }

    private function attendance(Activity $activity, ActivityParticipation $participation, User $manager, string $status): ActivityAttendance
    {
        return ActivityAttendance::create(['activity_session_id' => $activity->sessions()->firstOrFail()->getKey(), 'participation_id' => $participation->getKey(),
            'attendance_status' => $status, 'recorded_by' => $manager->getKey()]);
    }

    private function youth(): User
    {
        $user = User::factory()->create();
        $user->assignRole('youth');

        return $this->completeYouthOnboarding($user);
    }

    private function participation(Activity $activity, User $user, string $registration = ActivityParticipation::REGISTRATION_ACCEPTED, string $completion = ActivityParticipation::COMPLETION_PENDING): ActivityParticipation
    {
        return ActivityParticipation::create(['activity_id' => $activity->getKey(), 'user_id' => $user->getKey(), 'activity_role' => 'participant',
            'registration_status' => $registration, 'completion_status' => $completion,
            'completed_at' => $completion === ActivityParticipation::COMPLETION_COMPLETED ? now() : null, 'requested_at' => now()]);
    }

    private function membership(Organization $community, User $user, string $role, User $approver): void
    {
        OrganizationMembership::create(['organization_id' => $community->getKey(), 'user_id' => $user->getKey(), 'access_role' => $role,
            'membership_status' => OrganizationMembership::STATUS_ACTIVE, 'requested_at' => now(), 'approved_at' => now(), 'approved_by' => $approver->getKey()]);
    }
}
