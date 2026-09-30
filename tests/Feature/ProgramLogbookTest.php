<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationMembership;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\ProgramLogbook;
use App\Models\ProgramLogbookMedia;
use App\Models\ProgramProposal;
use App\Models\User;
use App\Services\ProgramProgressService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProgramLogbookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        Storage::fake('program_logbook_media');
    }

    public function test_manager_can_start_approved_program_without_mutating_proposal_or_activity(): void
    {
        [$organization,$manager,$program,$activity,$proposal] = $this->context('start');
        $this->actingAs($manager)->post(route('manager.programs.start-execution', [$organization, $program]))->assertRedirect();
        $this->assertSame(Program::STATUS_RUNNING, $program->fresh()->execution_status);
        $this->assertSame(ProgramProposal::STATUS_APPROVED, $proposal->fresh()->status);
        $this->assertSame(Activity::EXECUTION_SCHEDULED, $activity->fresh()->execution_status);
        $this->assertDatabaseHas('activity_log', ['event' => 'program_execution_started']);
        $this->actingAs($manager)->post(route('manager.programs.start-execution', [$organization, $program]))->assertForbidden();
    }

    public function test_execution_start_requires_approved_latest_proposal_and_activity(): void
    {
        [$organization,$manager,$program,, $proposal] = $this->context('gate');
        $proposal->forceFill(['status' => ProgramProposal::STATUS_REVISION])->save();
        $this->actingAs($manager)->post(route('manager.programs.start-execution', [$organization, $program]))->assertForbidden();
        $proposal->forceFill(['status' => ProgramProposal::STATUS_APPROVED])->save();
        $program->activities()->update(['program_id' => null]);
        $this->actingAs($manager)->post(route('manager.programs.start-execution', [$organization, $program]))->assertForbidden();
    }

    public function test_manager_can_create_update_render_and_archive_draft_logbook(): void
    {
        [$organization,$manager,$program,$activity] = $this->runningContext('crud');
        $this->actingAs($manager)->get(route('manager.program-logbooks.create', [$organization, $program]))->assertOk()->assertSee('Catat pelaksanaan Program');
        $this->actingAs($manager)->post(route('manager.program-logbooks.store', [$organization, $program]), $this->data($activity, ['created_by_user_id' => User::factory()->create()->uuid(), 'status' => 'approved']))->assertRedirect();
        $logbook = ProgramLogbook::firstOrFail();
        $this->assertSame($manager->getKey(), $logbook->created_by_user_id);
        $this->assertSame(ProgramLogbook::STATUS_DRAFT, $logbook->status);
        $this->assertSame(16, strlen($logbook->getKey()));
        $this->actingAs($manager)->patch(route('manager.program-logbooks.update', [$organization, $program, $logbook]), $this->data($activity, ['summary' => 'Ringkasan baru']))->assertRedirect();
        $this->assertSame('Ringkasan baru', $logbook->fresh()->summary);
        $this->actingAs($manager)->get(route('manager.program-logbooks.show', [$organization, $program, $logbook]))->assertOk()->assertSee('Ringkasan baru');
        $this->actingAs($manager)->delete(route('manager.program-logbooks.destroy', [$organization, $program, $logbook]))->assertRedirect();
        $this->assertSoftDeleted($logbook);
    }

    public function test_cross_context_member_outsider_and_wrong_activity_are_denied(): void
    {
        [$organization,$manager,$program,$activity] = $this->runningContext('secure');
        [, $otherManager,$otherProgram,$otherActivity] = $this->runningContext('other');
        $member = $this->roleUser('youth');
        $this->membership($organization, $member, OrganizationMembership::ROLE_MEMBER, $manager);
        $this->actingAs($member)->post(route('manager.program-logbooks.store', [$organization, $program]), $this->data($activity))->assertForbidden();
        $this->actingAs($otherManager)->post(route('manager.program-logbooks.store', [$organization, $program]), $this->data($activity))->assertForbidden();
        $this->actingAs($manager)->post(route('manager.program-logbooks.store', [$organization, $program]), $this->data($otherActivity))->assertSessionHasErrors('activity_id');
        $this->assertDatabaseCount('program_logbooks', 0);
    }

    public function test_submitted_and_approved_entries_are_locked_and_revision_is_editable(): void
    {
        [$organization,$manager,$program,$activity] = $this->runningContext('lifecycle');
        $logbook = $this->logbook($program, $activity, $manager);
        $this->actingAs($manager)->post(route('manager.program-logbooks.submit', [$organization, $program, $logbook]))->assertRedirect();
        $this->assertSame(ProgramLogbook::STATUS_SUBMITTED, $logbook->fresh()->status);
        $this->assertNotNull($logbook->fresh()->submitted_at);
        $this->actingAs($manager)->get(route('manager.program-logbooks.edit', [$organization, $program, $logbook]))->assertForbidden();
        $logbook->forceFill(['status' => ProgramLogbook::STATUS_REVISION, 'reviewed_at' => now(), 'review_notes' => 'Perjelas solusi.'])->save();
        $this->actingAs($manager)->get(route('manager.program-logbooks.edit', [$organization, $program, $logbook]))->assertOk();
        $program->forceFill(['execution_status' => Program::STATUS_COMPLETED])->save();
        $this->actingAs($manager)->get(route('manager.program-logbooks.edit', [$organization, $program, $logbook]))->assertForbidden();
    }

    public function test_private_media_upload_stream_and_cross_logbook_idor(): void
    {
        [$organization,$manager,$program,$activity] = $this->runningContext('media');
        $logbook = $this->logbook($program, $activity, $manager);
        $this->actingAs($manager)->post(route('manager.program-logbooks.media.store', [$organization, $program, $logbook]), ['file' => UploadedFile::fake()->create('evidence.pdf', 100, 'application/pdf'), 'caption' => 'Bukti'])->assertRedirect();
        $media = ProgramLogbookMedia::firstOrFail();
        Storage::disk('program_logbook_media')->assertExists($media->file_path);
        $this->assertArrayNotHasKey('file_path', $media->toArray());
        $this->actingAs($manager)->get(route('manager.program-logbooks.media.show', [$organization, $program, $logbook, $media]))->assertOk();
        $other = $this->logbook($program, $activity, $manager, now()->subDay()->toDateString());
        $this->actingAs($manager)->get(route('manager.program-logbooks.media.show', [$organization, $program, $other, $media]))->assertNotFound();
        $this->actingAs($manager)->post(route('manager.program-logbooks.media.store', [$organization, $program, $logbook]), ['file' => UploadedFile::fake()->create('script.exe', 10, 'application/octet-stream')])->assertSessionHasErrors('file');
        $this->actingAs($manager)->post(route('manager.program-logbooks.media.store', [$organization, $program, $logbook]), ['file' => UploadedFile::fake()->create('large.pdf', 9000, 'application/pdf')])->assertSessionHasErrors('file');
    }

    public function test_verifier_reviews_submitted_logbook_and_manager_receives_notification(): void
    {
        [$organization,$manager,$program,$activity] = $this->runningContext('review');
        $logbook = $this->logbook($program, $activity, $manager, now()->toDateString(), ProgramLogbook::STATUS_SUBMITTED);
        $verifier = $this->roleUser('verifier');
        $this->actingAs($verifier)->get(route('verifier.program-monitoring.index'))->assertOk()->assertSee($program->title);
        $this->actingAs($verifier)->get(route('verifier.program-monitoring.show', $logbook))->assertOk();
        $draft = $this->logbook($program, $activity, $manager, now()->subDays(2)->toDateString());
        $this->actingAs($verifier)->get(route('verifier.program-monitoring.index', ['status' => 'all']))->assertDontSee($draft->summary);
        $this->actingAs($verifier)->get(route('verifier.program-monitoring.show', $draft))->assertForbidden();
        $this->actingAs($verifier)->post(route('verifier.program-monitoring.review', $logbook), ['decision' => 'revision'])->assertSessionHasErrors('notes');
        $this->actingAs($verifier)->post(route('verifier.program-monitoring.review', $logbook), ['decision' => 'revision', 'notes' => 'Lengkapi dokumentasi.'])->assertRedirect();
        $this->assertSame(ProgramLogbook::STATUS_REVISION, $logbook->fresh()->status);
        $this->assertDatabaseHas('notifications', ['user_id' => $manager->getKey(), 'notification_type' => 'program_logbook_reviewed']);
        $this->actingAs($verifier)->post(route('verifier.program-monitoring.review', $logbook), ['decision' => 'approved'])->assertForbidden();
        $this->actingAs($manager)->get(route('verifier.program-monitoring.index'))->assertForbidden();
        $approved = $this->logbook($program, $activity, $manager, now()->subDay()->toDateString(), ProgramLogbook::STATUS_SUBMITTED);
        $this->actingAs($verifier)->post(route('verifier.program-monitoring.review', $approved), ['decision' => 'approved'])->assertRedirect();
        $this->assertSame(ProgramLogbook::STATUS_APPROVED, $approved->fresh()->status);
    }

    public function test_monitoring_metrics_are_factual_and_do_not_mutate_related_domains(): void
    {
        [, $manager,$program,$activity,$proposal] = $this->runningContext('metrics');
        $activity->forceFill(['execution_status' => Activity::EXECUTION_COMPLETED])->save();
        $this->logbook($program, $activity, $manager, now()->toDateString(), ProgramLogbook::STATUS_APPROVED, 60);
        $metrics = app(ProgramProgressService::class)->summarize($program->fresh());
        $this->assertSame(1, $metrics['activity_total']);
        $this->assertSame(1, $metrics['activity_completed']);
        $this->assertSame(1, $metrics['logbook_total']);
        $this->assertSame(60, $metrics['latest_reported_progress']);
        $this->assertSame(ProgramProposal::STATUS_APPROVED, $proposal->fresh()->status);
        $this->assertSame(Activity::EXECUTION_COMPLETED, $activity->fresh()->execution_status);
    }

    public function test_phase_boundary_tables_are_correct(): void
    {
        $this->assertTrue(Schema::hasTable('program_logbooks'));
        $this->assertTrue(Schema::hasTable('program_logbook_media'));
        $this->assertTrue(Schema::hasTable('financial_reports'));
        $this->assertTrue(Schema::hasTable('financial_items'));
        $this->assertTrue(Schema::hasTable('program_evaluations'));
    }

    private function runningContext(string $suffix): array
    {
        $context = $this->context($suffix);
        $context[2]->forceFill(['execution_status' => Program::STATUS_RUNNING])->save();

        return $context;
    }

    private function context(string $suffix): array
    {
        $manager = $this->roleUser('youth');
        $orgCategory = OrganizationCategory::create(['name' => 'Org '.$suffix, 'slug' => 'org-'.$suffix]);
        $organization = Organization::create(['created_by_user_id' => $manager->getKey(), 'category_id' => $orgCategory->getKey(), 'name' => 'Komunitas '.$suffix, 'slug' => 'community-'.$suffix, 'description' => 'Aktif', 'social_links' => (object) [], 'review_status' => Organization::REVIEW_APPROVED, 'operational_status' => Organization::OPERATIONAL_ACTIVE, 'approved_at' => now()]);
        $this->membership($organization, $manager, OrganizationMembership::ROLE_LEADER, $manager);
        $category = ProgramCategory::create(['name' => 'Program '.$suffix, 'slug' => 'program-'.$suffix]);
        $program = Program::create(['organization_id' => $organization->getKey(), 'category_id' => $category->getKey(), 'created_by_user_id' => $manager->getKey(), 'title' => 'Program '.$suffix, 'slug' => 'program-'.$suffix, 'execution_status' => Program::STATUS_PLANNED]);
        $activityCategory = ActivityCategory::firstOrCreate(['slug' => 'activity-'.$suffix], ['name' => 'Activity '.$suffix]);
        $activity = Activity::create(['organization_id' => $organization->getKey(), 'program_id' => $program->getKey(), 'category_id' => $activityCategory->getKey(), 'created_by_user_id' => $manager->getKey(), 'title' => 'Activity '.$suffix, 'slug' => 'activity-'.$suffix, 'location_type' => 'offline', 'start_at' => now()->addDay(), 'end_at' => now()->addDay()->addHour(), 'registration_mode' => 'open', 'review_status' => Activity::REVIEW_APPROVED, 'publication_status' => Activity::PUBLICATION_UNPUBLISHED, 'execution_status' => Activity::EXECUTION_SCHEDULED]);
        $proposal = ProgramProposal::create(['program_id' => $program->getKey(), 'version' => 1, 'status' => ProgramProposal::STATUS_APPROVED, 'submitted_at' => now()->subDay(), 'reviewed_at' => now()]);

        return [$organization, $manager, $program, $activity, $proposal];
    }

    private function membership(Organization $organization, User $user, string $role, User $approver): void
    {
        OrganizationMembership::create(['organization_id' => $organization->getKey(), 'user_id' => $user->getKey(), 'access_role' => $role, 'membership_status' => OrganizationMembership::STATUS_ACTIVE, 'requested_at' => now(), 'approved_at' => now(), 'approved_by' => $approver->getKey()]);
    }

    private function roleUser(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    private function data(Activity $activity, array $override = []): array
    {
        return array_merge(['activity_id' => $activity->uuid(), 'log_date' => now()->toDateString(), 'summary' => 'Pelaksanaan berjalan baik.', 'obstacles' => 'Hambatan kecil.', 'solutions' => 'Koordinasi lanjutan.', 'progress_percent' => 40], $override);
    }

    private function logbook(Program $program, Activity $activity, User $creator, ?string $date = null, string $status = ProgramLogbook::STATUS_DRAFT, int $progress = 40): ProgramLogbook
    {
        return ProgramLogbook::create(['program_id' => $program->getKey(), 'activity_id' => $activity->getKey(), 'created_by_user_id' => $creator->getKey(), 'log_date' => $date ?? now()->toDateString(), 'summary' => 'Pelaksanaan berjalan baik.', 'progress_percent' => $progress, 'status' => $status, 'submitted_at' => $status === ProgramLogbook::STATUS_DRAFT ? null : now()]);
    }
}
