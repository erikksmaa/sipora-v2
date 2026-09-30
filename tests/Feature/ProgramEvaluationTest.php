<?php

namespace Tests\Feature;

use App\Actions\Manager\SaveActivityDraftAction;
use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\FinancialItem;
use App\Models\FinancialReport;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationMembership;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\ProgramEvaluation;
use App\Models\ProgramLogbook;
use App\Models\ProgramProposal;
use App\Models\User;
use App\Services\ProgramEvaluationEligibilityService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ProgramEvaluationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_eligibility_requires_running_program_approved_latest_proposal_logbook_and_financial_report(): void
    {
        [, , $program] = $this->context('eligible');
        $service = app(ProgramEvaluationEligibilityService::class);
        $this->assertTrue($service->assess($program)['eligible']);

        $program->forceFill(['execution_status' => Program::STATUS_CANCELLED])->save();
        $this->assertFalse($service->assess($program)['eligible']);
        $program->forceFill(['execution_status' => Program::STATUS_RUNNING])->save();
        $program->latestProposal()->firstOrFail()->forceFill(['status' => ProgramProposal::STATUS_REVISION])->save();
        $this->assertFalse($service->assess($program)['eligible']);
        $program->latestProposal()->firstOrFail()->forceFill(['status' => ProgramProposal::STATUS_APPROVED])->save();
        $program->logbooks()->delete();
        $this->assertFalse($service->assess($program)['eligible']);
    }

    public function test_latest_proposal_and_financial_versions_must_be_approved(): void
    {
        [, , $program] = $this->context('versions');
        $service = app(ProgramEvaluationEligibilityService::class);
        ProgramProposal::create(['program_id' => $program->getKey(), 'version' => 2, 'status' => ProgramProposal::STATUS_SUBMITTED, 'submitted_at' => now()]);
        $this->assertFalse($service->assess($program)['eligible']);
        $program->proposals()->where('version', 2)->update(['status' => ProgramProposal::STATUS_APPROVED, 'reviewed_at' => now()]);
        FinancialReport::create(['program_id' => $program->getKey(), 'version' => 2, 'status' => FinancialReport::STATUS_REVISION, 'submitted_at' => now(), 'reviewed_at' => now()]);
        $assessment = $service->assess($program);
        $this->assertFalse($assessment['eligible']);
        $this->assertSame(2, $assessment['latest_proposal']->version);
        $this->assertSame(2, $assessment['latest_financial_report']->version);
    }

    public function test_verifier_can_open_consolidated_evidence_and_other_global_roles_are_denied(): void
    {
        [, $manager, $program] = $this->context('authorization');
        $verifier = $this->roleUser('verifier');
        $this->actingAs($verifier)->get(route('verifier.program-evaluations.index'))->assertOk()->assertSee($program->title);
        $this->actingAs($verifier)->get(route('verifier.program-evaluations.show', $program))->assertOk()->assertSee('Ringkasan E-LPJ')->assertSee('Bukti pelaksanaan');
        $this->actingAs($manager)->get(route('verifier.program-evaluations.show', $program))->assertForbidden();
        $this->actingAs($this->roleUser('admin'))->get(route('verifier.program-evaluations.show', $program))->assertForbidden();
        $this->app['auth']->forgetGuards();
        $this->get(route('verifier.program-evaluations.show', $program))->assertRedirect(route('login'));
    }

    public function test_approved_evaluation_completes_program_once_with_audit_and_notification(): void
    {
        [$organization, $manager, $program, $activity, $proposal, $report] = $this->context('complete');
        $verifier = $this->roleUser('verifier');
        $response = $this->actingAs($verifier)->post(route('verifier.program-evaluations.store', $program), ['decision' => 'approved', 'evaluation_notes' => 'Seluruh bukti memenuhi persyaratan.']);
        $response->assertRedirect();
        $evaluation = ProgramEvaluation::firstOrFail();
        $this->assertSame(ProgramEvaluation::DECISION_APPROVED, $evaluation->decision);
        $this->assertSame(Program::STATUS_COMPLETED, $program->fresh()->execution_status);
        $this->assertSame(Activity::REVIEW_APPROVED, $activity->fresh()->review_status);
        $this->assertSame(ProgramProposal::STATUS_APPROVED, $proposal->fresh()->status);
        $this->assertSame(FinancialReport::STATUS_APPROVED, $report->fresh()->status);
        $this->assertDatabaseHas('activity_log', ['event' => 'program_completed']);
        $this->assertDatabaseHas('notifications', ['user_id' => $manager->getKey(), 'notification_type' => 'program_evaluation_finalized']);
        $this->actingAs($verifier)->post(route('verifier.program-evaluations.store', $program), ['decision' => 'approved'])->assertForbidden();
        $this->assertDatabaseCount('program_evaluations', 1);
        $this->actingAs($manager)->get(route('manager.programs.show', [$organization, $program]))->assertOk()->assertSee('Program selesai')->assertSee('Seluruh bukti memenuhi persyaratan.');
    }

    public function test_revision_and_rejection_require_notes_leave_program_running_and_replay_is_blocked(): void
    {
        [, , $program] = $this->context('revision');
        $verifier = $this->roleUser('verifier');
        $this->actingAs($verifier)->post(route('verifier.program-evaluations.store', $program), ['decision' => 'revision'])->assertSessionHasErrors('evaluation_notes');
        $payload = ['decision' => 'revision', 'evaluation_notes' => 'Lengkapi tindak lanjut hasil kegiatan.'];
        $this->actingAs($verifier)->post(route('verifier.program-evaluations.store', $program), $payload)->assertRedirect();
        $this->assertSame(Program::STATUS_RUNNING, $program->fresh()->execution_status);
        $this->actingAs($verifier)->post(route('verifier.program-evaluations.store', $program), $payload)->assertSessionHasErrors('evaluation');
        $latest = ProgramEvaluation::firstOrFail();
        $this->actingAs($verifier)->post(route('verifier.program-evaluations.store', $program), ['decision' => 'rejected', 'evaluation_notes' => 'Hasil akhir belum memenuhi.', 'expected_latest_evaluation_id' => $latest->uuid()])->assertRedirect();
        $this->assertSame(Program::STATUS_RUNNING, $program->fresh()->execution_status);
        $this->assertDatabaseCount('program_evaluations', 2);
    }

    public function test_conflicted_verifier_and_forged_cross_program_token_are_denied(): void
    {
        [$organization, $manager, $program] = $this->context('conflict');
        $manager->assignRole('verifier');
        $this->actingAs($manager)->post(route('verifier.program-evaluations.store', $program), ['decision' => 'approved'])->assertForbidden();

        [, , $otherProgram] = $this->context('other-evaluation');
        $otherEvaluation = ProgramEvaluation::create(['program_id' => $otherProgram->getKey(), 'verifier_id' => $manager->getKey(), 'decision' => ProgramEvaluation::DECISION_REVISION, 'evaluated_at' => now()]);
        $verifier = $this->roleUser('verifier');
        $this->actingAs($verifier)->post(route('verifier.program-evaluations.store', $program), ['decision' => 'approved', 'expected_latest_evaluation_id' => $otherEvaluation->uuid()])->assertSessionHasErrors('evaluation');
        $this->assertSame(Program::STATUS_RUNNING, $program->fresh()->execution_status);
        $this->assertSame($organization->getKey(), $program->organization_id);
    }

    public function test_completed_program_locks_program_logbook_finance_and_activity_relinking(): void
    {
        [$organization, $manager, $program, $activity, , $report] = $this->context('immutable');
        $program->forceFill(['execution_status' => Program::STATUS_COMPLETED])->save();
        $this->actingAs($manager)->get(route('manager.programs.edit', [$organization, $program]))->assertForbidden();
        $this->actingAs($manager)->get(route('manager.program-logbooks.create', [$organization, $program]))->assertForbidden();
        $this->actingAs($manager)->get(route('manager.financial-reports.edit', [$organization, $program, $report]))->assertForbidden();

        $otherProgram = Program::create(['organization_id' => $organization->getKey(), 'category_id' => $program->category_id, 'created_by_user_id' => $manager->getKey(), 'title' => 'Program tujuan', 'slug' => 'program-tujuan', 'execution_status' => Program::STATUS_PLANNED]);
        $data = $activity->only(['title', 'description', 'location_type', 'venue_name', 'address_text', 'meeting_url', 'start_at', 'end_at', 'registration_open_at', 'registration_close_at', 'quota', 'registration_mode', 'min_age', 'max_age', 'requires_identity_verification', 'members_only', 'eligibility_notes', 'certificate_enabled']);
        $data += ['category_id' => $activity->category->uuid(), 'program_id' => $otherProgram->uuid(), 'administrative_area_id' => null];
        $this->expectException(ValidationException::class);
        app(SaveActivityDraftAction::class)->execute($manager, $organization, $data, null, $activity);
    }

    public function test_schema_and_public_boundary_are_preserved(): void
    {
        $this->assertTrue(Schema::hasTable('program_evaluations'));
        $this->assertFalse(Schema::hasTable('opportunities'));
        $routes = collect(app('router')->getRoutes()->getRoutes());
        $this->assertFalse($routes->contains(fn ($route) => str_starts_with((string) $route->getName(), 'public.program-evaluations')));
    }

    private function context(string $suffix): array
    {
        $manager = $this->roleUser('youth');
        $orgCategory = OrganizationCategory::create(['name' => 'Org '.$suffix, 'slug' => 'org-'.$suffix]);
        $organization = Organization::create(['created_by_user_id' => $manager->getKey(), 'category_id' => $orgCategory->getKey(), 'name' => 'Komunitas '.$suffix, 'slug' => 'community-'.$suffix, 'description' => 'Aktif', 'social_links' => (object) [], 'review_status' => Organization::REVIEW_APPROVED, 'operational_status' => Organization::OPERATIONAL_ACTIVE, 'approved_at' => now()]);
        OrganizationMembership::create(['organization_id' => $organization->getKey(), 'user_id' => $manager->getKey(), 'access_role' => OrganizationMembership::ROLE_LEADER, 'membership_status' => OrganizationMembership::STATUS_ACTIVE, 'requested_at' => now(), 'approved_at' => now(), 'approved_by' => $manager->getKey()]);
        $category = ProgramCategory::create(['name' => 'Program '.$suffix, 'slug' => 'program-'.$suffix]);
        $program = Program::create(['organization_id' => $organization->getKey(), 'category_id' => $category->getKey(), 'created_by_user_id' => $manager->getKey(), 'title' => 'Program '.$suffix, 'slug' => 'program-'.$suffix, 'execution_status' => Program::STATUS_RUNNING, 'start_date' => now()->subMonth(), 'end_date' => now()]);
        $activityCategory = ActivityCategory::firstOrCreate(['slug' => 'activity-'.$suffix], ['name' => 'Activity '.$suffix]);
        $activity = Activity::create(['organization_id' => $organization->getKey(), 'program_id' => $program->getKey(), 'category_id' => $activityCategory->getKey(), 'created_by_user_id' => $manager->getKey(), 'title' => 'Activity '.$suffix, 'slug' => 'activity-'.$suffix, 'location_type' => 'offline', 'venue_name' => 'Gedung Pemuda', 'start_at' => now()->subWeek(), 'end_at' => now()->subWeek()->addHour(), 'registration_mode' => 'open', 'review_status' => Activity::REVIEW_APPROVED, 'publication_status' => Activity::PUBLICATION_UNPUBLISHED, 'execution_status' => Activity::EXECUTION_COMPLETED]);
        $proposal = ProgramProposal::create(['program_id' => $program->getKey(), 'version' => 1, 'status' => ProgramProposal::STATUS_APPROVED, 'requested_budget' => '1000000.00', 'submitted_at' => now()->subDays(10), 'reviewed_at' => now()->subDays(9)]);
        ProgramLogbook::create(['program_id' => $program->getKey(), 'activity_id' => $activity->getKey(), 'created_by_user_id' => $manager->getKey(), 'log_date' => now()->subDays(5)->toDateString(), 'summary' => 'Pelaksanaan selesai.', 'progress_percent' => 100, 'status' => ProgramLogbook::STATUS_APPROVED, 'submitted_at' => now()->subDays(4), 'reviewed_at' => now()->subDays(3)]);
        $report = FinancialReport::create(['program_id' => $program->getKey(), 'version' => 1, 'status' => FinancialReport::STATUS_APPROVED, 'submitted_at' => now()->subDays(2), 'reviewed_at' => now()->subDay()]);
        FinancialItem::create(['financial_report_id' => $report->getKey(), 'transaction_type' => FinancialItem::TYPE_EXPENSE, 'transaction_date' => now()->subWeek()->toDateString(), 'description' => 'Realisasi', 'amount' => '900000.00']);

        return [$organization, $manager, $program, $activity, $proposal, $report];
    }

    private function roleUser(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }
}
