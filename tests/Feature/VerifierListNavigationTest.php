<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\VerifierQueueSummaryService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\ProgramIntakeFixtures;
use Tests\TestCase;

class VerifierListNavigationTest extends TestCase
{
    use ProgramIntakeFixtures, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_verifier_lists_default_to_all_and_explicit_pending_filters_still_work(): void
    {
        [$org, $manager, $program] = $this->intakeFixture();
        $program->logbooks()->create(['created_by_user_id' => $manager->getKey(), 'log_date' => now()->toDateString(), 'summary' => 'Approved historical logbook', 'progress_percent' => 100, 'status' => 'approved', 'submitted_at' => now(), 'reviewed_at' => now()]);
        $program->financialReports()->create(['version' => 1, 'status' => 'approved', 'submitted_at' => now(), 'reviewed_at' => now()]);
        $verifier = User::factory()->create()->assignRole('verifier');
        foreach (['community-verifications', 'activity-verifications', 'program-proposals', 'program-monitoring', 'financial-reports'] as $route) {
            $this->actingAs($verifier)->get(route('verifier.'.$route.'.index'))->assertOk()->assertViewHas('status', 'all');
            $pending = in_array($route, ['community-verifications', 'activity-verifications']) ? 'pending_review' : 'submitted';
            $this->get(route('verifier.'.$route.'.index', ['status' => $pending]))->assertOk()->assertViewHas('status', $pending);
        }
        $this->get(route('verifier.program-monitoring.index'))->assertOk()->assertViewHas('logbooks', fn ($rows) => $rows->total() === 1);
        $this->get(route('verifier.financial-reports.index'))->assertOk()->assertViewHas('reports', fn ($rows) => $rows->total() === 1);
    }

    public function test_sidebar_indicators_show_only_positive_counts_and_do_not_change_default_links(): void
    {
        [$org, $manager, $program] = $this->intakeFixture();
        $verifier = User::factory()->create()->assignRole('verifier');
        $this->actingAs($verifier)->get(route('verifier.dashboard'))->assertOk()->assertDontSee('data-queue-indicator=', false);
        $program->proposals()->create(['version' => 2, 'status' => 'submitted', 'submitted_at' => now()]);
        $this->get(route('verifier.dashboard'))->assertOk()
            ->assertSee('data-queue-indicator="'.route('verifier.program-proposals.index').'"', false)
            ->assertSee('1 perlu ditindaklanjuti')
            ->assertSee('bg-warning-soft', false)
            ->assertSee(route('verifier.program-proposals.index', ['status' => 'submitted']), false);
        $this->get(route('verifier.program-proposals.index'))->assertOk()->assertViewHas('status', 'all')->assertViewHas('proposals', fn ($rows) => $rows->total() === 2);
        $admin = User::factory()->create()->assignRole('admin');
        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk()->assertDontSee('data-queue-indicator=', false);
    }

    public function test_application_indicator_counts_only_pending_review_of_eligible_non_conflicting_programs(): void
    {
        [$org, $manager, $program] = $this->intakeFixture();
        $program->applications()->create(['user_id' => $manager->getKey(), 'status' => 'submitted']);
        $verifier = User::factory()->create()->assignRole('verifier');
        $service = app(VerifierQueueSummaryService::class);
        $this->assertSame(1, $service->navigation($verifier)[route('verifier.programs.intake.index')]);
        $this->assertSame(0, $service->navigation($manager)[route('verifier.programs.intake.index')]);
        $program->applications()->update(['status' => 'accepted']);
        $this->assertSame(0, $service->navigation($verifier)[route('verifier.programs.intake.index')]);
    }
}
