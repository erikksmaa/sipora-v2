<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationMembership;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\User;
use App\Support\BinaryUuid;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProgramCoreTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_contextual_owner_can_create_update_and_render_program_draft(): void
    {
        [$organization, $manager] = $this->community('owner');
        $category = $this->programCategory('capacity');

        $this->actingAs($manager)->get(route('manager.programs.create', $organization))->assertOk()->assertSee('Buat Rencana Program');
        $this->actingAs($manager)->post(route('manager.programs.store', $organization), $this->validData($category, [
            'organization_id' => BinaryUuid::text(BinaryUuid::generate()),
            'execution_status' => Program::STATUS_COMPLETED,
        ]))->assertRedirect();

        $program = Program::firstOrFail();
        $this->assertSame(16, strlen($program->getKey()));
        $this->assertSame($organization->getKey(), $program->organization_id);
        $this->assertSame($manager->getKey(), $program->created_by_user_id);
        $this->assertSame(Program::STATUS_PLANNED, $program->execution_status);
        $this->assertTrue($program->category->is($category));

        $this->actingAs($manager)->patch(route('manager.programs.update', [$organization, $program]), $this->validData($category, ['title' => 'Program Diperbarui']))->assertRedirect();
        $this->assertSame('Program Diperbarui', $program->fresh()->title);
        $this->actingAs($manager)->get(route('manager.programs.show', [$organization, $program]))->assertOk()->assertSee('Belum ada Activity');
        $this->assertDatabaseHas('activity_log', ['event' => 'program_created']);
        $this->assertDatabaseHas('activity_log', ['event' => 'program_updated']);
    }

    public function test_member_outsider_cross_organization_manager_admin_and_verifier_are_denied(): void
    {
        [$organization, $manager] = $this->community('protected');
        [$otherOrganization, $otherManager] = $this->community('other');
        $member = $this->roleUser('youth');
        $this->membership($organization, $member, OrganizationMembership::ROLE_MEMBER, $manager);
        $program = $this->program($organization, $manager, $this->programCategory('protected'));

        $this->actingAs($member)->get(route('manager.programs.show', [$organization, $program]))->assertForbidden();
        $this->actingAs($otherManager)->get(route('manager.programs.show', [$organization, $program]))->assertForbidden();
        $this->actingAs($manager)->get(route('manager.programs.show', [$otherOrganization, $program]))->assertNotFound();
        $this->actingAs($this->roleUser('admin'))->get(route('manager.programs.index', $organization))->assertForbidden();
        $this->actingAs($this->roleUser('verifier'))->get(route('manager.programs.index', $organization))->assertForbidden();
    }

    public function test_invalid_program_data_is_rejected_and_non_planned_program_is_locked(): void
    {
        [$organization, $manager] = $this->community('validation');
        $category = $this->programCategory('validation');
        $this->actingAs($manager)->post(route('manager.programs.store', $organization), $this->validData($category, [
            'title' => '', 'start_date' => '2027-04-10', 'end_date' => '2027-04-01',
        ]))->assertSessionHasErrors(['title', 'end_date']);

        $program = $this->program($organization, $manager, $category, Program::STATUS_RUNNING);
        $this->actingAs($manager)->get(route('manager.programs.edit', [$organization, $program]))->assertForbidden();
    }

    public function test_activity_may_be_standalone_or_linked_to_same_organization_program(): void
    {
        [$organization, $manager] = $this->community('activity');
        $program = $this->program($organization, $manager, $this->programCategory('activity'));
        $activityCategory = $this->activityCategory();

        $this->actingAs($manager)->post(route('manager.activities.store', $organization), $this->activityData($activityCategory))->assertRedirect();
        $standalone = Activity::firstOrFail();
        $this->assertNull($standalone->program_id);

        $this->actingAs($manager)->post(route('manager.activities.store', $organization), $this->activityData($activityCategory, [
            'title' => 'Activity Program', 'program_id' => $program->uuid(),
        ]))->assertRedirect();
        $linked = Activity::query()->where('title', 'Activity Program')->firstOrFail();
        $this->assertTrue($linked->program->is($program));
        $this->assertDatabaseHas('activity_log', ['event' => 'activity_linked_to_program']);
        $this->actingAs($manager)->get(route('manager.programs.show', [$organization, $program]))->assertOk()->assertSee('Activity Program');
    }

    public function test_cross_context_program_link_is_rejected(): void
    {
        [$organization, $manager] = $this->community('source');
        [$otherOrganization, $otherManager] = $this->community('target');
        $foreignProgram = $this->program($otherOrganization, $otherManager, $this->programCategory('foreign'));

        $this->actingAs($manager)->post(route('manager.activities.store', $organization), $this->activityData($this->activityCategory(), [
            'program_id' => $foreignProgram->uuid(),
        ]))->assertSessionHasErrors('program_id');
        $this->assertDatabaseCount('activities', 0);
    }

    public function test_database_rejects_invalid_program_foreign_key(): void
    {
        [$organization, $manager] = $this->community('foreign-key');
        $category = $this->activityCategory();

        $this->expectException(QueryException::class);
        DB::table('activities')->insert([
            'id' => BinaryUuid::generate(), 'organization_id' => $organization->getKey(), 'program_id' => BinaryUuid::generate(),
            'category_id' => $category->getKey(), 'created_by_user_id' => $manager->getKey(), 'title' => 'Invalid', 'slug' => 'invalid-program-fk',
            'location_type' => 'offline', 'start_at' => now()->addDay(), 'end_at' => now()->addDay()->addHour(),
            'registration_mode' => 'open', 'review_status' => 'draft', 'publication_status' => 'unpublished', 'execution_status' => 'scheduled',
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    public function test_implemented_and_future_program_workflow_boundaries_are_preserved(): void
    {
        $this->assertTrue(Schema::hasTable('program_proposals'));
        $this->assertTrue(Schema::hasTable('program_logbooks'));
        $this->assertTrue(Schema::hasTable('program_logbook_media'));

        $this->assertTrue(Schema::hasTable('financial_reports'));
        $this->assertTrue(Schema::hasTable('financial_items'));
        $this->assertTrue(Schema::hasTable('program_evaluations'));

        $routes = collect(app('router')->getRoutes()->getRoutes())->map->getName()->filter();
        $this->assertTrue($routes->contains(fn (string $name): bool => str_contains($name, 'program-proposals')));
        $this->assertTrue($routes->contains(fn (string $name): bool => str_contains($name, 'program-logbooks')));
        $this->assertTrue($routes->contains(fn (string $name): bool => str_contains($name, 'financial')));
        $this->assertTrue($routes->contains(fn (string $name): bool => str_contains($name, 'evaluation')));
    }

    private function roleUser(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $role === 'youth' ? $this->completeYouthOnboarding($user) : $user;
    }

    private function community(string $slug): array
    {
        $manager = $this->roleUser('youth');
        $category = OrganizationCategory::create(['name' => 'Kategori '.$slug, 'slug' => 'category-'.$slug]);
        $organization = Organization::create([
            'created_by_user_id' => $manager->getKey(), 'category_id' => $category->getKey(), 'name' => 'Komunitas '.$slug,
            'slug' => 'community-'.$slug, 'description' => 'Komunitas aktif', 'social_links' => (object) [],
            'review_status' => Organization::REVIEW_APPROVED, 'operational_status' => Organization::OPERATIONAL_ACTIVE, 'approved_at' => now(),
        ]);
        $this->membership($organization, $manager, OrganizationMembership::ROLE_LEADER, $manager);

        return [$organization, $manager];
    }

    private function membership(Organization $organization, User $user, string $role, User $approver): void
    {
        OrganizationMembership::create([
            'organization_id' => $organization->getKey(), 'user_id' => $user->getKey(), 'access_role' => $role,
            'membership_status' => OrganizationMembership::STATUS_ACTIVE, 'requested_at' => now(), 'approved_at' => now(), 'approved_by' => $approver->getKey(),
        ]);
    }

    private function programCategory(string $slug): ProgramCategory
    {
        return ProgramCategory::firstOrCreate(['slug' => 'program-'.$slug], ['name' => 'Program '.$slug]);
    }

    private function activityCategory(): ActivityCategory
    {
        return ActivityCategory::firstOrCreate(['slug' => 'kepemudaan'], ['name' => 'Kepemudaan']);
    }

    private function program(Organization $organization, User $creator, ProgramCategory $category, string $status = Program::STATUS_PLANNED): Program
    {
        return Program::create([
            'organization_id' => $organization->getKey(), 'category_id' => $category->getKey(), 'created_by_user_id' => $creator->getKey(),
            'title' => 'Program '.$organization->slug, 'slug' => 'program-'.$organization->slug, 'description' => 'Deskripsi Program',
            'objectives' => 'Tujuan Program', 'execution_status' => $status,
        ]);
    }

    private function validData(ProgramCategory $category, array $override = []): array
    {
        return array_merge([
            'category_id' => $category->uuid(), 'title' => 'Program Pemuda Digital', 'description' => 'Deskripsi Program.',
            'objectives' => 'Meningkatkan kapasitas pemuda.', 'start_date' => '2027-01-01', 'end_date' => '2027-03-31',
        ], $override);
    }

    private function activityData(ActivityCategory $category, array $override = []): array
    {
        return array_merge([
            'category_id' => $category->uuid(), 'title' => 'Activity Mandiri', 'description' => 'Deskripsi Activity.',
            'location_type' => 'offline', 'venue_name' => 'Gedung Pemuda', 'start_at' => now()->addDays(7)->format('Y-m-d H:i:s'),
            'end_at' => now()->addDays(7)->addHours(2)->format('Y-m-d H:i:s'), 'registration_mode' => 'open',
        ], $override);
    }
}
