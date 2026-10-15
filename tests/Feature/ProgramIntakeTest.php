<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\User;
use App\Models\UserNotification;
use App\Notifications\ProgramApplicationChanged;
use App\Services\NotificationActionResolver;
use App\Services\Program\ProgramIntakeService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\Support\ProgramIntakeFixtures;
use Tests\TestCase;

class ProgramIntakeTest extends TestCase
{
    use ProgramIntakeFixtures, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        Notification::fake();
    }

    public function test_create_and_configuration_are_optional_and_validate_quota_and_dates(): void
    {
        [$org,$manager,$program] = $this->intakeFixture();
        $this->actingAs($manager)->get(route('manager.programs.intake.edit', [$org, $program]))->assertOk();
        $route = route('manager.programs.intake.update', [$org, $program]);
        $this->patch($route, ['registration_enabled' => false, 'selection_mode' => 'open', 'quota' => null])->assertRedirect();
        $this->assertFalse($program->fresh()->registration_enabled);
        $this->patch($route, ['registration_enabled' => true, 'selection_mode' => 'review', 'quota' => 0])->assertSessionHasErrors('quota');
        $this->patch($route, ['registration_open_at' => '2027-02-02T10:00', 'registration_close_at' => '2027-02-01T10:00'])->assertSessionHasErrors('registration_close_at');
        $this->patch($route, ['registration_enabled' => true, 'selection_mode' => 'review', 'quota' => 5, 'registration_open_at' => '2027-02-01T10:00', 'registration_close_at' => '2027-02-02T10:00'])->assertRedirect();
        $this->assertSame('2027-02-01 03:00:00', $program->fresh()->registration_open_at->format('Y-m-d H:i:s'));
        $this->patch($route, ['registration_close_at' => '2027-02-01T08:00'])->assertSessionHasErrors('registration_close_at');
        $this->patch($route, ['registration_open_at' => null, 'registration_close_at' => '2027-02-02T10:00'])->assertRedirect();
        $this->assertNull($program->fresh()->registration_open_at);
        $this->post(route('manager.programs.store', $org), ['category_id' => $program->category->uuid(), 'title' => 'Default intake off'])->assertRedirect();
        $this->assertFalse(Program::where('title', 'Default intake off')->firstOrFail()->registration_enabled);
    }

    public function test_open_registration_accepts_and_consumes_quota_without_activity_enrollment(): void
    {
        [$org,$manager,$program] = $this->intakeFixture(['selection_mode' => 'open']);
        $user = $this->intakeYouth();
        $this->actingAs($user)->post(route('youth.programs.apply', $program))->assertRedirect(route('programs.show', $program));
        $this->assertSame('accepted', $program->applications()->first()->status);
        $this->assertSame(1, $program->participants()->count());
        $this->assertDatabaseCount('activity_participations', 0);
        $this->post(route('youth.programs.apply', $program))->assertSessionHasErrors('registration');
        $this->actingAs($this->intakeYouth())->post(route('youth.programs.apply', $program))->assertSessionHasErrors('registration');
        $this->assertSame(0, app(ProgramIntakeService::class)->summary($program)['remaining']);
        Notification::assertSentTo($user, ProgramApplicationChanged::class);
        $this->actingAs($user)->get(route('programs.show', $program))->assertOk()->assertSee('Diterima');
    }

    public function test_review_applications_can_exceed_quota_and_pending_waitlisted_rejected_do_not_consume_it(): void
    {
        [$org,$manager,$program] = $this->intakeFixture();
        $intake = app(ProgramIntakeService::class);
        $applicants = [];
        for ($i = 0; $i < 3; $i++) {
            $applicants[] = $intake->apply($this->intakeYouth(), $program);
        }
        $this->assertSame(3, $program->applications()->count());
        $this->assertSame(0, $program->participants()->count());
        $intake->review($manager, $program, $applicants[0], 'under_review', 'submitted', null);
        $intake->review($manager, $program, $applicants[0], 'waitlisted', 'under_review', 'Tunggu kapasitas');
        $intake->review($manager, $program, $applicants[1], 'rejected', 'submitted', 'Belum sesuai');
        $this->assertSame(1, $intake->summary($program)['remaining']);
        $intake->review($manager, $program, $applicants[2], 'accepted', 'submitted', null);
        $this->assertSame(1, $program->participants()->count());
        $this->assertSame(0, $intake->summary($program)['remaining']);
        $this->actingAs($manager)->post(route('manager.programs.applications.review', [$org, $program, $applicants[0]]), ['decision' => 'accepted', 'expected_status' => 'waitlisted'])->assertSessionHasErrors('decision');
        $this->assertSame('waitlisted', $applicants[0]->fresh()->status);
        $this->get(route('manager.programs.applications.index', [$org, $program, 'status' => 'accepted']))->assertOk()->assertViewHas('applications', fn ($rows) => $rows->total() === 1 && $rows->first()->status === 'accepted');
        $this->assertSame(3, $program->applications()->count());
    }

    public function test_windows_disabled_unpublished_completed_and_ineligible_users_are_blocked(): void
    {
        [$org,$manager,$program] = $this->intakeFixture();
        $user = $this->intakeYouth();
        $this->actingAs($user);
        foreach ([['registration_open_at' => now()->addDay()], ['registration_open_at' => null, 'registration_close_at' => now()->subDay()], ['registration_close_at' => null, 'registration_enabled' => false], ['registration_enabled' => true, 'execution_status' => 'completed'], ['execution_status' => 'cancelled'], ['execution_status' => 'planned']] as $change) {
            $program->update($change);
            $this->post(route('youth.programs.apply', $program))->assertSessionHasErrors('registration');
        }
        $program->update(['execution_status' => 'running']);
        $org->update(['operational_status' => 'inactive']);
        $this->post(route('youth.programs.apply', $program))->assertSessionHasErrors('registration');
        $org->update(['operational_status' => 'active']);
        $incomplete = User::factory()->create()->assignRole('youth');
        $this->actingAs($incomplete)->post(route('youth.programs.apply', $program))->assertRedirect();
        $this->assertDatabaseCount('program_applications', 0);
        $this->actingAs(User::factory()->create()->assignRole('admin'))->post(route('youth.programs.apply', $program))->assertForbidden();
    }

    public function test_authorization_cross_program_and_stale_review_are_enforced_server_side(): void
    {
        [$org,$manager,$program] = $this->intakeFixture();
        [$otherOrg,$otherManager,$otherProgram] = $this->intakeFixture();
        $user = $this->intakeYouth();
        $app = app(ProgramIntakeService::class)->apply($user, $program);
        $route = route('manager.programs.applications.review', [$org, $program, $app]);
        $data = ['decision' => 'accepted', 'expected_status' => 'submitted'];
        $this->actingAs($user)->post($route, $data)->assertForbidden();
        $this->actingAs($otherManager)->post($route, $data)->assertForbidden();
        $this->actingAs($manager)->post(route('manager.programs.applications.review', [$org, $program, $otherProgram->applications()->create(['user_id' => $user->getKey(), 'status' => 'submitted'])]), $data)->assertNotFound();
        $this->post($route, $data)->assertRedirect();
        $this->post($route, $data)->assertSessionHasErrors('decision');
        $this->post($route, ['decision' => 'rejected', 'expected_status' => 'accepted'])->assertSessionHasErrors('decision');
        $this->assertSame('accepted', $app->fresh()->status);
        $this->actingAs($user)->post(route('youth.programs.withdraw', $program))->assertRedirect();
        $this->assertSame('withdrawn', $app->fresh()->status);
        $this->assertSame(0, $program->participants()->count());
        $this->post(route('youth.programs.apply', $program))->assertSessionHasErrors('registration');
    }

    public function test_verifier_uses_shared_review_and_private_fields_are_not_exposed(): void
    {
        [$org,$manager,$program] = $this->intakeFixture();
        $user = $this->intakeYouth();
        $user->profile()->update(['full_name' => 'Hidden private name', 'bio' => 'Hidden private bio']);
        $user->profileVisibility()->create(['is_profile_public' => false]);
        $app = app(ProgramIntakeService::class)->apply($user, $program);
        $verifier = User::factory()->create()->assignRole('verifier');
        $this->actingAs($verifier)->get(route('verifier.programs.applications.index', $program))->assertOk()->assertDontSee('Hidden private name')->assertDontSee('Hidden private bio')->assertDontSee($user->email);
        $this->post(route('verifier.programs.applications.review', [$program, $app]), ['decision' => 'accepted', 'expected_status' => 'submitted', 'notes' => 'Diterima'])->assertRedirect();
        $this->assertSame('accepted', $app->fresh()->status);
        $this->get(route('verifier.programs.intake.index'))->assertOk();
        $manager->assignRole('verifier');
        $this->actingAs($manager)->get(route('verifier.programs.applications.index', $program))->assertForbidden();
        $this->actingAs(User::factory()->create()->assignRole('admin'))->get(route('manager.programs.applications.index', [$org, $program]))->assertForbidden();
    }

    public function test_configuration_cannot_shrink_accepted_quota_or_change_mode_with_existing_applications(): void
    {
        [$org,$manager,$program] = $this->intakeFixture(['selection_mode' => 'open']);
        app(ProgramIntakeService::class)->apply($this->intakeYouth(), $program);
        $this->actingAs($manager)->patch(route('manager.programs.intake.update', [$org, $program]), ['selection_mode' => 'review'])->assertSessionHasErrors('selection_mode');
        $program->update(['quota' => 2]);
        app(ProgramIntakeService::class)->apply($this->intakeYouth(), $program);
        $this->patch(route('manager.programs.intake.update', [$org, $program]), ['quota' => 1])->assertSessionHasErrors('quota');
        $this->assertSame(2, $program->fresh()->quota);
        $this->get(route('manager.programs.applications.index', [$org, $program]))->assertOk();
    }

    public function test_applicant_filters_search_pagination_and_unique_constraint(): void
    {
        [$org, $manager, $program] = $this->intakeFixture(['quota' => null]);
        for ($i = 0; $i < 16; $i++) {
            $user = User::factory()->create(['name' => 'Pagination Youth '.$i]);
            $program->applications()->create(['user_id' => $user->getKey(), 'status' => 'submitted']);
        }
        $route = route('manager.programs.applications.index', [$org, $program]);
        $this->actingAs($manager)->get($route)->assertOk()->assertViewHas('applications', fn ($page) => $page->total() === 16 && $page->count() === 15);
        $this->get($route.'?page=2')->assertOk()->assertViewHas('applications', fn ($page) => $page->count() === 1);
        $this->get($route.'?q=Pagination%20Youth%2015')->assertOk()->assertViewHas('applications', fn ($page) => $page->total() === 1);
        $this->expectException(QueryException::class);
        $program->applications()->create(['user_id' => $user->getKey(), 'status' => 'submitted']);
    }

    public function test_program_notification_links_require_ownership_and_safe_identifiers(): void
    {
        [$org, $manager, $program] = $this->intakeFixture();
        $user = $this->intakeYouth();
        app(ProgramIntakeService::class)->apply($user, $program);
        $notification = new UserNotification(['notification_type' => 'program_application_result', 'data' => (object) ['program_id' => $program->uuid()]]);
        $resolver = app(NotificationActionResolver::class);
        $this->assertSame(route('programs.show', $program), $resolver->resolve($user, $notification));
        $this->assertNull($resolver->resolve($manager, $notification));
        $notification->data = (object) ['program_id' => ['invalid']];
        $this->assertNull($resolver->resolve($user, $notification));
    }
}
