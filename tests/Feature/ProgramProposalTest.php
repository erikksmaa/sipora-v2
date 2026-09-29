<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationMembership;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\ProgramProposal;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProgramProposalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        Storage::fake('proposal_documents');
    }

    public function test_manager_can_create_edit_and_submit_complete_proposal(): void
    {
        [$organization, $manager, $program] = $this->context('submission');
        $this->activity($organization, $manager, $program);

        $this->actingAs($manager)->get(route('manager.program-proposals.create', [$organization, $program]))->assertOk()->assertSee('Buat draft Proposal');
        $this->actingAs($manager)->post(route('manager.program-proposals.store', [$organization, $program]), [
            'requested_budget' => 12500000, 'proposal_document' => UploadedFile::fake()->create('proposal.pdf', 100, 'application/pdf'),
        ])->assertRedirect();
        $proposal = ProgramProposal::firstOrFail();
        $this->assertSame(16, strlen($proposal->getKey()));
        $this->assertSame(ProgramProposal::STATUS_DRAFT, $proposal->status);
        Storage::disk('proposal_documents')->assertExists($proposal->proposal_document_path);

        $this->actingAs($manager)->patch(route('manager.program-proposals.update', [$organization, $program, $proposal]), ['requested_budget' => 15000000])->assertRedirect();
        $this->actingAs($manager)->post(route('manager.program-proposals.submit', [$organization, $program, $proposal]))->assertRedirect();
        $proposal->refresh();
        $this->assertSame(ProgramProposal::STATUS_SUBMITTED, $proposal->status);
        $this->assertNotNull($proposal->submitted_at);
        $this->assertSame(Program::STATUS_PLANNED, $program->fresh()->execution_status);
        $this->assertDatabaseHas('activity_log', ['event' => 'program_proposal_submitted']);
        $verifier = $this->roleUser('verifier');
        $this->actingAs($verifier)->get(route('verifier.program-proposals.index'))->assertOk()->assertSee($manager->name);
        $this->actingAs($manager)->patch(route('manager.program-proposals.update', [$organization, $program, $proposal]), ['requested_budget' => 1])->assertForbidden();
        $this->actingAs($manager)->post(route('manager.program-proposals.submit', [$organization, $program, $proposal]))->assertForbidden();
    }

    public function test_submission_requires_document_and_linked_activity(): void
    {
        [$organization, $manager, $program] = $this->context('incomplete');
        $proposal = $this->proposal($program, ProgramProposal::STATUS_DRAFT, null);
        $this->actingAs($manager)->post(route('manager.program-proposals.submit', [$organization, $program, $proposal]))
            ->assertSessionHasErrors(['activities', 'proposal_document']);
        $this->assertSame(ProgramProposal::STATUS_DRAFT, $proposal->fresh()->status);
    }

    public function test_cross_community_manager_and_ordinary_youth_are_denied(): void
    {
        [$organization, $manager, $program] = $this->context('protected');
        $proposal = $this->proposal($program, ProgramProposal::STATUS_DRAFT);
        [, $outsider] = $this->context('outside');
        $member = $this->roleUser('youth');

        $this->actingAs($outsider)->get(route('manager.program-proposals.edit', [$organization, $program, $proposal]))->assertForbidden();
        $this->actingAs($member)->get(route('manager.program-proposals.edit', [$organization, $program, $proposal]))->assertForbidden();
        $this->actingAs($manager)->get(route('manager.program-proposals.edit', [$organization, $program, $proposal]))->assertOk();
        [, , $foreignProgram] = $this->context('forged');
        $this->actingAs($manager)->get(route('manager.program-proposals.edit', [$organization, $program, $this->proposal($foreignProgram, ProgramProposal::STATUS_DRAFT)]))->assertNotFound();
    }

    public function test_verifier_can_approve_without_changing_program_execution_or_activity_status(): void
    {
        [$organization, $manager, $program] = $this->context('approve');
        $activity = $this->activity($organization, $manager, $program);
        $proposal = $this->proposal($program, ProgramProposal::STATUS_SUBMITTED);
        $verifier = $this->roleUser('verifier');

        $this->actingAs($verifier)->get(route('verifier.program-proposals.index'))->assertOk()->assertSee($program->title);
        $this->actingAs($verifier)->post(route('verifier.program-proposals.review', $proposal), ['decision' => 'approved'])->assertRedirect();
        $this->assertSame(ProgramProposal::STATUS_APPROVED, $proposal->fresh()->status);
        $this->assertSame(Program::STATUS_PLANNED, $program->fresh()->execution_status);
        $this->assertSame(Activity::REVIEW_DRAFT, $activity->fresh()->review_status);
        $this->assertDatabaseHas('notifications', ['user_id' => $manager->getKey(), 'notification_type' => 'program_proposal_reviewed']);
        $this->assertDatabaseHas('activity_log', ['event' => 'program_proposal_approved']);
    }

    public function test_revision_requires_note_and_creates_new_version_for_resubmission(): void
    {
        [$organization, $manager, $program] = $this->context('revision');
        $this->activity($organization, $manager, $program);
        $proposal = $this->proposal($program, ProgramProposal::STATUS_SUBMITTED);
        $verifier = $this->roleUser('verifier');

        $this->actingAs($verifier)->post(route('verifier.program-proposals.review', $proposal), ['decision' => 'revision'])->assertSessionHasErrors('notes');
        $this->actingAs($verifier)->post(route('verifier.program-proposals.review', $proposal), ['decision' => 'revision', 'notes' => 'Perbarui rincian dokumen.'])->assertRedirect();
        $this->actingAs($manager)->get(route('manager.programs.show', [$organization, $program]))->assertOk()->assertSee('Perbarui rincian dokumen.');
        $this->actingAs($manager)->post(route('manager.program-proposals.revise', [$organization, $program, $proposal]))->assertRedirect();
        $revision = ProgramProposal::query()->where('program_id', $program->getKey())->where('version', 2)->firstOrFail();
        $this->assertSame(ProgramProposal::STATUS_REVISION, $proposal->fresh()->status);
        $this->assertSame(ProgramProposal::STATUS_DRAFT, $revision->status);
        $this->assertNotSame($proposal->proposal_document_path, $revision->proposal_document_path);
        Storage::disk('proposal_documents')->assertExists($revision->proposal_document_path);
        $this->actingAs($manager)->post(route('manager.program-proposals.submit', [$organization, $program, $revision]))->assertRedirect();
        $this->assertSame(ProgramProposal::STATUS_SUBMITTED, $revision->fresh()->status);
        $this->assertDatabaseHas('activity_log', ['event' => 'program_proposal_resubmitted']);
    }

    public function test_terminal_and_duplicate_review_decisions_are_blocked(): void
    {
        [, , $program] = $this->context('terminal');
        $proposal = $this->proposal($program, ProgramProposal::STATUS_SUBMITTED);
        $verifier = $this->roleUser('verifier');
        $this->actingAs($verifier)->post(route('verifier.program-proposals.review', $proposal), ['decision' => 'rejected', 'notes' => 'Tidak memenuhi ketentuan.'])->assertRedirect();
        $this->actingAs($verifier)->post(route('verifier.program-proposals.review', $proposal), ['decision' => 'approved'])->assertSessionHasErrors('decision');
        $this->assertSame(ProgramProposal::STATUS_REJECTED, $proposal->fresh()->status);
    }

    public function test_review_authorization_notes_and_forged_fields_are_enforced(): void
    {
        [, $manager, $program] = $this->context('review-security');
        $proposal = $this->proposal($program, ProgramProposal::STATUS_SUBMITTED);
        $verifier = $this->roleUser('verifier');

        $this->actingAs($manager)->post(route('verifier.program-proposals.review', $proposal), ['decision' => 'approved'])->assertForbidden();
        $this->actingAs($verifier)->post(route('verifier.program-proposals.review', $proposal), ['decision' => 'rejected'])->assertSessionHasErrors('notes');
        $this->actingAs($verifier)->post(route('verifier.program-proposals.review', $proposal), [
            'decision' => 'approved', 'reviewed_by' => $manager->uuid(), 'status' => 'rejected', 'reviewed_at' => now()->subYear()->toDateTimeString(),
        ])->assertRedirect();
        $proposal->refresh();
        $this->assertSame(ProgramProposal::STATUS_APPROVED, $proposal->status);
        $this->assertSame($verifier->getKey(), $proposal->reviewed_by);
        $this->assertGreaterThan(now()->subMinute(), $proposal->reviewed_at);
    }

    public function test_composition_changes_are_locked_during_review(): void
    {
        [$organization, $manager, $program] = $this->context('lock');
        $activity = $this->activity($organization, $manager, $program);
        $this->proposal($program, ProgramProposal::STATUS_SUBMITTED);

        $this->actingAs($manager)->patch(route('manager.activities.update', [$organization, $activity]), $this->activityData($activity->category, ['program_id' => null]))
            ->assertSessionHasErrors('program_id');
        $this->assertTrue($activity->fresh()->program->is($program));
        $this->actingAs($manager)->get(route('manager.programs.edit', [$organization, $program]))->assertForbidden();
    }

    public function test_documents_are_private_and_role_authorized(): void
    {
        [$organization, $manager, $program] = $this->context('document');
        $proposal = $this->proposal($program, ProgramProposal::STATUS_SUBMITTED);
        $verifier = $this->roleUser('verifier');
        $youth = $this->roleUser('youth');

        $this->actingAs($manager)->get(route('manager.program-proposals.document', [$organization, $program, $proposal]))->assertOk()->assertHeader('Cache-Control', 'max-age=0, no-store, private');
        $this->actingAs($verifier)->get(route('verifier.program-proposals.document', $proposal))->assertOk();
        $this->actingAs($youth)->get(route('verifier.program-proposals.document', $proposal))->assertForbidden();
        $this->assertArrayNotHasKey('proposal_document_path', $proposal->toArray());
    }

    public function test_schema_and_future_boundaries_are_preserved(): void
    {
        $this->assertTrue(Schema::hasTable('program_proposals'));
        $this->assertTrue(Schema::hasTable('program_logbooks'));
        $this->assertTrue(Schema::hasTable('program_logbook_media'));

        foreach (['financial_reports', 'financial_items', 'program_evaluations'] as $table) {
            $this->assertFalse(Schema::hasTable($table));
        }
    }

    public function test_manager_verifier_conflict_is_blocked_server_side(): void
    {
        [$organization, $manager, $program] = $this->context('conflict');
        $manager->assignRole('verifier');
        $proposal = $this->proposal($program, ProgramProposal::STATUS_SUBMITTED);

        $this->actingAs($manager)->post(route('verifier.program-proposals.review', $proposal), ['decision' => 'approved'])->assertForbidden();
        $this->assertSame(ProgramProposal::STATUS_SUBMITTED, $proposal->fresh()->status);
    }

    private function context(string $suffix): array
    {
        $manager = $this->roleUser('youth');
        $organizationCategory = OrganizationCategory::create(['name' => 'Kategori '.$suffix, 'slug' => 'org-'.$suffix]);
        $organization = Organization::create(['created_by_user_id' => $manager->getKey(), 'category_id' => $organizationCategory->getKey(), 'name' => 'Komunitas '.$suffix,
            'slug' => 'community-'.$suffix, 'description' => 'Aktif', 'social_links' => (object) [], 'review_status' => Organization::REVIEW_APPROVED,
            'operational_status' => Organization::OPERATIONAL_ACTIVE, 'approved_at' => now()]);
        OrganizationMembership::create(['organization_id' => $organization->getKey(), 'user_id' => $manager->getKey(), 'access_role' => OrganizationMembership::ROLE_LEADER,
            'membership_status' => OrganizationMembership::STATUS_ACTIVE, 'requested_at' => now(), 'approved_at' => now(), 'approved_by' => $manager->getKey()]);
        $category = ProgramCategory::create(['name' => 'Program '.$suffix, 'slug' => 'program-category-'.$suffix]);
        $program = Program::create(['organization_id' => $organization->getKey(), 'category_id' => $category->getKey(), 'created_by_user_id' => $manager->getKey(),
            'title' => 'Program '.$suffix, 'slug' => 'program-'.$suffix, 'description' => 'Deskripsi', 'objectives' => 'Tujuan', 'execution_status' => Program::STATUS_PLANNED]);

        return [$organization, $manager, $program];
    }

    private function proposal(Program $program, string $status, ?string $path = 'proposals/example.pdf'): ProgramProposal
    {
        if ($path) {
            Storage::disk('proposal_documents')->put($path, '%PDF test');
        }
        $reviewed = in_array($status, [ProgramProposal::STATUS_APPROVED, ProgramProposal::STATUS_REVISION, ProgramProposal::STATUS_REJECTED], true);

        return ProgramProposal::create(['program_id' => $program->getKey(), 'version' => 1, 'status' => $status, 'requested_budget' => 1000000,
            'proposal_document_path' => $path, 'submitted_at' => $status === ProgramProposal::STATUS_DRAFT ? null : now(),
            'reviewed_at' => $reviewed ? now() : null, 'review_notes' => $status === ProgramProposal::STATUS_REVISION ? 'Revisi.' : null]);
    }

    private function activity(Organization $organization, User $manager, Program $program): Activity
    {
        $category = ActivityCategory::firstOrCreate(['slug' => 'category-'.$organization->slug], ['name' => 'Activity']);

        return Activity::create(['organization_id' => $organization->getKey(), 'program_id' => $program->getKey(), 'category_id' => $category->getKey(), 'created_by_user_id' => $manager->getKey(),
            'title' => 'Activity '.$organization->slug, 'slug' => 'activity-'.$organization->slug, 'location_type' => 'offline', 'venue_name' => 'Gedung',
            'start_at' => now()->addWeek(), 'end_at' => now()->addWeek()->addHours(2), 'registration_mode' => 'open', 'review_status' => Activity::REVIEW_DRAFT,
            'publication_status' => Activity::PUBLICATION_UNPUBLISHED, 'execution_status' => Activity::EXECUTION_SCHEDULED]);
    }

    private function activityData(ActivityCategory $category, array $override = []): array
    {
        return array_merge(['category_id' => $category->uuid(), 'title' => 'Activity updated', 'location_type' => 'offline', 'venue_name' => 'Gedung',
            'start_at' => now()->addWeek()->format('Y-m-d H:i:s'), 'end_at' => now()->addWeek()->addHours(2)->format('Y-m-d H:i:s'), 'registration_mode' => 'open'], $override);
    }

    private function roleUser(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }
}
