<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\AdministrativeArea;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\ProgramProposal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicProgramTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_programs_with_public_status_approved_latest_proposal_and_active_community_are_visible(): void
    {
        [$organization, $category, $creator] = $this->context();
        $running = $this->program($organization, $category, $creator, 'Program Berjalan', Program::STATUS_RUNNING, ProgramProposal::STATUS_APPROVED);
        $completed = $this->program($organization, $category, $creator, 'Program Selesai', Program::STATUS_COMPLETED, ProgramProposal::STATUS_APPROVED);
        $planned = $this->program($organization, $category, $creator, 'Program Rencana', Program::STATUS_PLANNED, ProgramProposal::STATUS_APPROVED);
        $pending = $this->program($organization, $category, $creator, 'Program Menunggu', Program::STATUS_RUNNING, ProgramProposal::STATUS_SUBMITTED);

        $this->get(route('programs.index'))->assertOk()
            ->assertSee($running->title)->assertSee($completed->title)
            ->assertDontSee($planned->title)->assertDontSee($pending->title);
        $this->get(route('programs.show', $running))->assertOk()->assertSee('Program Berjalan');
        $this->get(route('programs.show', $planned))->assertNotFound();
        $this->get('/programs/tidak-ada')->assertNotFound();
    }

    public function test_latest_proposal_must_be_approved_and_private_review_data_is_never_rendered(): void
    {
        [$organization, $category, $creator] = $this->context();
        $program = $this->program($organization, $category, $creator, 'Program Aman', Program::STATUS_RUNNING, ProgramProposal::STATUS_APPROVED, [
            'proposal_document_path' => 'private/proposals/rahasia.pdf',
            'review_notes' => 'CATATAN_INTERNAL_RAHASIA',
            'requested_budget' => 125000000,
        ]);

        $this->get(route('programs.show', $program))->assertOk()
            ->assertDontSee('private/proposals/rahasia.pdf')
            ->assertDontSee('CATATAN_INTERNAL_RAHASIA')
            ->assertDontSee('125000000');

        ProgramProposal::create(['program_id' => $program->getKey(), 'version' => 2, 'status' => ProgramProposal::STATUS_REVISION, 'submitted_at' => now(), 'reviewed_at' => now(), 'review_notes' => 'Perbaiki dokumen']);
        $this->get(route('programs.show', $program))->assertNotFound();
    }

    public function test_inactive_community_hides_an_otherwise_eligible_program(): void
    {
        [$organization, $category, $creator] = $this->context();
        $program = $this->program($organization, $category, $creator, 'Program Tidak Aktif', Program::STATUS_RUNNING, ProgramProposal::STATUS_APPROVED);
        $organization->update(['operational_status' => Organization::OPERATIONAL_INACTIVE]);

        $this->get(route('programs.show', $program))->assertNotFound();
    }

    public function test_program_detail_links_only_published_approved_activities_and_its_public_community(): void
    {
        [$organization, $category, $creator] = $this->context();
        $program = $this->program($organization, $category, $creator, 'Program Bertaut', Program::STATUS_RUNNING, ProgramProposal::STATUS_APPROVED);
        $activityCategory = ActivityCategory::create(['name' => 'Pelatihan', 'slug' => 'pelatihan']);
        $start = now()->addWeek();
        $base = ['organization_id' => $organization->getKey(), 'program_id' => $program->getKey(), 'category_id' => $activityCategory->getKey(), 'created_by_user_id' => $creator->getKey(), 'description' => 'Aman.', 'location_type' => 'online', 'start_at' => $start, 'end_at' => $start->copy()->addHour(), 'registration_mode' => 'open', 'execution_status' => 'scheduled'];
        Activity::create(array_merge($base, ['title' => 'Activity Publik Program', 'slug' => 'activity-publik-program', 'review_status' => Activity::REVIEW_APPROVED, 'publication_status' => Activity::PUBLICATION_PUBLISHED, 'published_at' => now()]));
        Activity::create(array_merge($base, ['title' => 'Activity Draft Rahasia', 'slug' => 'activity-draft-rahasia', 'review_status' => 'draft', 'publication_status' => 'unpublished']));

        $this->get(route('programs.show', $program))->assertOk()
            ->assertSee('Activity Publik Program')->assertDontSee('Activity Draft Rahasia')
            ->assertSee(route('communities.show', $organization), false)
            ->assertSee(route('activities.show', 'activity-publik-program'), false);
    }

    private function context(): array
    {
        $creator = User::factory()->create();
        $area = AdministrativeArea::create(['code' => '33.27.08', 'name' => 'Pemalang', 'area_level' => 'district']);
        $organizationCategory = OrganizationCategory::create(['name' => 'Kepemudaan', 'slug' => 'kepemudaan']);
        $organization = Organization::create(['created_by_user_id' => $creator->getKey(), 'category_id' => $organizationCategory->getKey(), 'administrative_area_id' => $area->getKey(), 'name' => 'Community Publik', 'slug' => 'community-publik', 'description' => 'Aman.', 'review_status' => Organization::REVIEW_APPROVED, 'operational_status' => Organization::OPERATIONAL_ACTIVE, 'approved_at' => now()]);
        $category = ProgramCategory::create(['name' => 'Pemberdayaan', 'slug' => 'pemberdayaan']);

        return [$organization, $category, $creator];
    }

    private function program(Organization $organization, ProgramCategory $category, User $creator, string $title, string $executionStatus, string $proposalStatus, array $proposalOverrides = []): Program
    {
        $program = Program::create(['organization_id' => $organization->getKey(), 'category_id' => $category->getKey(), 'created_by_user_id' => $creator->getKey(), 'title' => $title, 'slug' => str($title)->slug(), 'description' => 'Deskripsi publik.', 'objectives' => 'Tujuan publik.', 'start_date' => today(), 'end_date' => today()->addMonth(), 'execution_status' => $executionStatus]);
        ProgramProposal::create(array_merge(['program_id' => $program->getKey(), 'version' => 1, 'status' => $proposalStatus, 'submitted_at' => now(), 'reviewed_at' => in_array($proposalStatus, [ProgramProposal::STATUS_APPROVED, ProgramProposal::STATUS_REVISION, ProgramProposal::STATUS_REJECTED], true) ? now() : null], $proposalOverrides));

        return $program;
    }
}
