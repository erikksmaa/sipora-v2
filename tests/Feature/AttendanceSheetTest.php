<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\OrganizationMembership;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\ProgramIntakeFixtures;
use Tests\TestCase;

class AttendanceSheetTest extends TestCase
{
    use ProgramIntakeFixtures, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_activity_sheet_authorization_eligibility_privacy_and_full_list(): void
    {
        [$org, $manager, $program] = $this->intakeFixture();
        $category = ActivityCategory::create(['name' => 'Print', 'slug' => 'print']);
        $activity = Activity::create(['organization_id' => $org->getKey(), 'category_id' => $category->getKey(), 'created_by_user_id' => $manager->getKey(), 'title' => 'Workshop Cetak', 'slug' => 'workshop-cetak', 'location_type' => 'offline', 'venue_name' => 'Gedung Pemuda', 'start_at' => '2026-10-18 02:00:00', 'end_at' => '2026-10-18 08:00:00']);
        for ($i = 55; $i >= 1; $i--) {
            $user = $this->participant(sprintf('Peserta %02d', $i));
            $activity->participations()->create(['user_id' => $user->getKey(), 'activity_role' => 'participant', 'registration_status' => 'accepted', 'requested_at' => now()]);
        }
        foreach (['pending', 'rejected', 'cancelled', 'deleted'] as $status) {
            $entry = $activity->participations()->create(['user_id' => $this->participant('Excluded '.$status)->getKey(), 'activity_role' => 'participant', 'registration_status' => $status === 'deleted' ? 'accepted' : $status, 'requested_at' => now()]);
            if ($status === 'deleted') {
                $entry->delete();
            }
        }
        $url = route('manager.activities.attendance-sheet', [$org, $activity]);
        $this->get($url)->assertRedirect(route('login'));
        $this->actingAs($this->intakeYouth())->get($url)->assertForbidden();
        $response = $this->actingAs($manager)->get($url.'?page=2&status=rejected');
        $response->assertOk()->assertSeeInOrder(['Peserta 01', 'Peserta 55'])->assertDontSee('Excluded')->assertDontSee('081234567899')->assertDontSee('No. HP')->assertSee('Gedung Pemuda');
        $this->assertCount(55, $response->viewData('rows'));
        $this->assertPrivate($response->getContent());
        $this->get($url.'?include_phone=1')->assertOk()->assertSee('No. HP')->assertSee('081234567899');
        [$otherOrg] = $this->intakeFixture();
        $this->get(route('manager.activities.attendance-sheet', [$otherOrg, $activity]))->assertNotFound();
        $this->assertDatabaseCount('activity_attendances', 0);
    }

    public function test_program_sheet_uses_only_accepted_program_applicants_for_manager_and_verifier(): void
    {
        [$org, $manager, $program] = $this->intakeFixture();
        foreach (['submitted', 'under_review', 'waitlisted', 'rejected', 'withdrawn'] as $status) {
            $program->applications()->create(['user_id' => $this->participant('Excluded '.$status)->getKey(), 'status' => $status]);
        }
        $url = route('manager.programs.attendance-sheet', [$org, $program]);
        foreach ([5, 30, 55] as $size) {
            for ($i = $program->participants()->count() + 1; $i <= $size; $i++) {
                $program->applications()->create(['user_id' => $this->participant(sprintf('Program %02d', $i))->getKey(), 'status' => 'accepted']);
            }
            $response = $this->actingAs($manager)->get($url.'?page=3&q=Excluded&status=withdrawn');
            $response->assertOk()->assertSee('DAFTAR HADIR PESERTA PROGRAM')->assertSeeInOrder(['Program 01', sprintf('Program %02d', $size)])->assertDontSee('Excluded');
            $this->assertCount($size, $response->viewData('rows'));
            $this->assertPrivate($response->getContent());
        }
        $verifier = User::factory()->create()->assignRole('verifier');
        $verifierUrl = route('verifier.programs.attendance-sheet', $program);
        $this->actingAs($verifier)->get($verifierUrl)->assertOk()->assertSee('Program 55');
        $program->latestProposal->update(['status' => 'draft']);
        $this->get($verifierUrl)->assertForbidden();
        $this->actingAs($this->intakeYouth())->get($url)->assertForbidden();
        $this->actingAs(User::factory()->create()->assignRole('admin'))->get($verifierUrl)->assertForbidden();
        [$otherOrg] = $this->intakeFixture();
        $this->actingAs($manager)->get(route('manager.programs.attendance-sheet', [$otherOrg, $program]))->assertNotFound();
        $this->assertDatabaseCount('activity_participations', 0);
    }

    public function test_active_leader_and_community_manager_can_see_buttons_and_print_both_sheets(): void
    {
        [$org, $leader, $program] = $this->intakeFixture();
        $category = ActivityCategory::create(['name' => 'Role Print', 'slug' => 'role-print']);
        $activity = Activity::create(['organization_id' => $org->getKey(), 'category_id' => $category->getKey(), 'created_by_user_id' => $leader->getKey(), 'title' => 'Role Workshop', 'slug' => 'role-workshop', 'location_type' => 'offline', 'start_at' => now(), 'end_at' => now()->addHour()]);
        $manager = $this->intakeYouth();
        $membership = OrganizationMembership::create(['organization_id' => $org->getKey(), 'user_id' => $manager->getKey(), 'membership_status' => 'active', 'access_role' => 'manager', 'approved_at' => now()]);
        $activityPrint = route('manager.activities.attendance-sheet', [$org, $activity]);
        $programPrint = route('manager.programs.attendance-sheet', [$org, $program]);

        foreach ([$leader, $manager] as $actor) {
            $this->actingAs($actor)->get(route('manager.activities.participants.index', [$org, $activity]))->assertOk()->assertSee('Cetak Daftar Hadir')->assertSee($activityPrint, false);
            $this->get(route('manager.programs.applications.index', [$org, $program]))->assertOk()->assertSee('Cetak Daftar Hadir')->assertSee($programPrint, false);
            $this->get($activityPrint)->assertOk()->assertSee('DAFTAR HADIR KEGIATAN');
            $this->get($programPrint)->assertOk()->assertSee('DAFTAR HADIR PESERTA PROGRAM');
        }

        $membership->update(['access_role' => 'member']);
        $this->actingAs($manager)->get($activityPrint)->assertForbidden();
        $this->get($programPrint)->assertForbidden();
        $membership->update(['access_role' => 'manager', 'membership_status' => 'removed']);
        $this->get($activityPrint)->assertForbidden();
        $this->get($programPrint)->assertForbidden();
    }

    public function test_empty_sheet_and_missing_phone_are_printable(): void
    {
        [$org, $manager, $program] = $this->intakeFixture();
        $url = route('manager.programs.attendance-sheet', [$org, $program]);
        $this->actingAs($manager)->get($url)->assertOk()->assertSee('Belum ada peserta yang diterima.');
        $user = User::factory()->create(['name' => 'Tanpa Telepon']);
        $program->applications()->create(['user_id' => $user->getKey(), 'status' => 'accepted']);
        $response = $this->get($url.'?include_phone=1')->assertOk()->assertSee('Tanpa Telepon');
        $this->assertNull($response->viewData('rows')->first()['phone']);
    }

    private function participant(string $name): User
    {
        $user = User::factory()->create(['name' => $name]);
        $user->profile()->create(['public_slug' => $user->uuid(), 'full_name' => $name, 'phone' => '081234567899', 'birth_place' => 'SECRET_PLACE', 'birth_date' => '2005-01-01']);

        return $user;
    }

    private function assertPrivate(string $html): void
    {
        foreach (['@example', '@sipora', 'SECRET_PLACE', '2005-01-01', 'national_id', 'document_number', 'floating-chat', '<nav', 'sidebar', '/resources/js/app'] as $private) {
            $this->assertStringNotContainsString($private, $html);
        }
        $this->assertStringContainsString('size: A4 portrait', $html);
        $this->assertStringContainsString('table-header-group', $html);
        $this->assertStringContainsString('break-inside: avoid', $html);
        $this->assertStringContainsString('window.print()', $html);
    }
}
