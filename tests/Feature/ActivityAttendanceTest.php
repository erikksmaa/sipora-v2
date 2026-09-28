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
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityAttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_manager_can_mark_and_update_attendance_without_changing_completion(): void
    {
        [$community, $manager, $activity, $session] = $this->context('mark');
        $participation = $this->participation($activity, $this->youth());
        $route = route('manager.activities.attendance.update', [$community, $activity, $session, $participation]);

        $this->actingAs($manager)->put($route, ['attendance_status' => 'present', 'checked_in_at' => now()->format('Y-m-d H:i:s')])->assertRedirect();
        $attendance = ActivityAttendance::firstOrFail();
        $this->assertSame(ActivityAttendance::STATUS_PRESENT, $attendance->attendance_status);
        $this->assertSame(16, strlen($attendance->getKey()));
        $this->assertSame(ActivityParticipation::COMPLETION_PENDING, $participation->fresh()->completion_status);

        $this->actingAs($manager)->put($route, ['attendance_status' => 'excused', 'notes' => 'Izin resmi'])->assertRedirect();
        $this->assertDatabaseCount('activity_attendances', 1);
        $this->assertSame(ActivityAttendance::STATUS_EXCUSED, $attendance->fresh()->attendance_status);
        $this->assertDatabaseHas('activity_log', ['event' => 'attendance_marked']);
        $this->assertDatabaseHas('activity_log', ['event' => 'attendance_updated']);
    }

    public function test_only_accepted_participants_are_valid_attendance_targets(): void
    {
        [$community, $manager, $activity, $session] = $this->context('status');
        foreach ([ActivityParticipation::REGISTRATION_PENDING, ActivityParticipation::REGISTRATION_REJECTED, ActivityParticipation::REGISTRATION_CANCELLED] as $status) {
            $participant = $this->participation($activity, $this->youth(), $status);
            $this->actingAs($manager)->put(route('manager.activities.attendance.update', [$community, $activity, $session, $participant]), ['attendance_status' => 'present'])
                ->assertSessionHasErrors('attendance');
        }
        $this->assertDatabaseCount('activity_attendances', 0);
    }

    public function test_cross_community_manager_and_wrong_session_are_rejected(): void
    {
        [$community, $manager, $activity, $session] = $this->context('protected');
        [$otherCommunity, $otherManager, $otherActivity, $otherSession] = $this->context('other');
        $participation = $this->participation($activity, $this->youth());

        $this->actingAs($otherManager)->put(route('manager.activities.attendance.update', [$community, $activity, $session, $participation]), ['attendance_status' => 'present'])->assertForbidden();
        $this->actingAs($manager)->get(route('manager.activities.attendance.index', [$community, $activity, $otherSession]))->assertNotFound();
        $this->actingAs($manager)->put(route('manager.activities.attendance.update', [$community, $activity, $otherSession, $participation]), ['attendance_status' => 'present'])->assertNotFound();
    }

    public function test_member_admin_verifier_and_guest_cannot_manage_attendance(): void
    {
        [$community, $manager, $activity, $session] = $this->context('roles');
        $member = $this->youth();
        $this->membership($community, $member, OrganizationMembership::ROLE_MEMBER, $manager);
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $verifier = User::factory()->create();
        $verifier->assignRole('verifier');
        $route = route('manager.activities.attendance.index', [$community, $activity, $session]);

        $this->get($route)->assertRedirectToRoute('login');
        $this->actingAs($member)->get($route)->assertForbidden();
        $this->actingAs($admin)->get($route)->assertForbidden();
        $this->actingAs($verifier)->get($route)->assertForbidden();
    }

    public function test_unique_record_is_updated_and_session_participant_combinations_remain_independent(): void
    {
        [$community, $manager, $activity, $firstSession] = $this->context('combinations');
        $secondSession = $this->activitySession($activity, 2);
        $first = $this->participation($activity, $this->youth());
        $second = $this->participation($activity, $this->youth());

        foreach ([[$firstSession, $first], [$firstSession, $second], [$secondSession, $first]] as [$session, $participation]) {
            $this->actingAs($manager)->put(route('manager.activities.attendance.update', [$community, $activity, $session, $participation]), ['attendance_status' => 'present'])->assertRedirect();
        }
        $this->assertDatabaseCount('activity_attendances', 3);
        $this->actingAs($manager)->put(route('manager.activities.attendance.update', [$community, $activity, $firstSession, $first]), ['attendance_status' => 'absent'])->assertRedirect();
        $this->assertDatabaseCount('activity_attendances', 3);
    }

    public function test_page_shows_only_accepted_participants_and_standard_stitch_controls(): void
    {
        [$community, $manager, $activity, $session] = $this->context('page');
        $accepted = $this->youth(['name' => 'Peserta Diterima']);
        $pending = $this->youth(['name' => 'Peserta Pending']);
        $this->participation($activity, $accepted);
        $this->participation($activity, $pending, ActivityParticipation::REGISTRATION_PENDING);

        $this->actingAs($manager)->get(route('manager.activities.attendance.index', [$community, $activity, $session]))
            ->assertOk()->assertSee('Manajemen Kehadiran')->assertSee('Pilih sesi')->assertSee('Peserta Diterima')->assertDontSee('Peserta Pending')->assertDontSee('QR');
    }

    public function test_database_rejects_duplicate_session_participation_pair_and_invalid_times(): void
    {
        [$community, $manager, $activity, $session] = $this->context('constraints');
        $participation = $this->participation($activity, $this->youth());
        $route = route('manager.activities.attendance.update', [$community, $activity, $session, $participation]);
        $this->actingAs($manager)->put($route, [
            'attendance_status' => 'present',
            'checked_in_at' => now()->format('Y-m-d H:i:s'),
            'checked_out_at' => now()->subMinute()->format('Y-m-d H:i:s'),
        ])->assertSessionHasErrors('checked_out_at');
        $attributes = ['activity_session_id' => $session->getKey(), 'participation_id' => $participation->getKey(), 'attendance_status' => 'present', 'recorded_by' => $manager->getKey()];
        ActivityAttendance::create($attributes);
        $this->expectException(QueryException::class);
        ActivityAttendance::create($attributes);
    }

    private function context(string $slug): array
    {
        $manager = $this->youth();
        $organizationCategory = OrganizationCategory::create(['name' => 'Kategori '.$slug, 'slug' => 'organization-'.$slug]);
        $community = Organization::create(['created_by_user_id' => $manager->getKey(), 'category_id' => $organizationCategory->getKey(), 'name' => 'Komunitas '.$slug,
            'slug' => 'community-'.$slug, 'description' => 'Komunitas aktif', 'social_links' => (object) [], 'review_status' => Organization::REVIEW_APPROVED,
            'operational_status' => Organization::OPERATIONAL_ACTIVE, 'approved_at' => now()]);
        $this->membership($community, $manager, OrganizationMembership::ROLE_LEADER, $manager);
        $category = ActivityCategory::firstOrCreate(['slug' => 'attendance-category'], ['name' => 'Kategori Presensi']);
        $activity = Activity::create(['organization_id' => $community->getKey(), 'category_id' => $category->getKey(), 'created_by_user_id' => $manager->getKey(),
            'title' => 'Activity '.$slug, 'slug' => 'activity-'.$slug, 'description' => 'Activity presensi.', 'location_type' => 'offline', 'venue_name' => 'Gedung Pemuda',
            'start_at' => now()->subDay(), 'end_at' => now()->addDay(), 'registration_mode' => 'open', 'review_status' => Activity::REVIEW_APPROVED,
            'publication_status' => Activity::PUBLICATION_PUBLISHED, 'execution_status' => Activity::EXECUTION_SCHEDULED, 'published_at' => now()]);

        return [$community, $manager, $activity, $this->activitySession($activity, 1)];
    }

    private function activitySession(Activity $activity, int $number): ActivitySession
    {
        return ActivitySession::create(['activity_id' => $activity->getKey(), 'session_number' => $number, 'title' => 'Sesi '.$number,
            'start_at' => now()->subHour(), 'end_at' => now()->addHour(), 'venue_name' => 'Gedung Pemuda']);
    }

    private function youth(array $attributes = []): User
    {
        $user = User::factory()->create($attributes);
        $user->assignRole('youth');

        return $user;
    }

    private function participation(Activity $activity, User $user, string $status = ActivityParticipation::REGISTRATION_ACCEPTED): ActivityParticipation
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
