<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationMembership;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ActivityWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_contextual_manager_can_create_edit_and_render_activity_draft_with_binary_relationships(): void
    {
        [$community, $manager] = $this->community('create');
        $category = $this->activityCategory();
        $this->actingAs($manager)->get(route('manager.activities.create', $community))->assertOk()->assertSee('Buat Activity');
        $this->actingAs($manager)->post(route('manager.activities.store', $community), $this->validData($category))->assertRedirect();
        $activity = Activity::firstOrFail();
        $this->assertSame(16, strlen($activity->getKey()));
        $this->assertSame(Activity::REVIEW_DRAFT, $activity->review_status);
        $this->assertSame(Activity::PUBLICATION_UNPUBLISHED, $activity->publication_status);
        $this->actingAs($manager)->patch(route('manager.activities.update', [$community, $activity]), $this->validData($category, ['title' => 'Activity Diperbarui']))->assertRedirect();
        $this->assertSame('Activity Diperbarui', $activity->fresh()->title);
    }

    public function test_member_outsider_and_manager_of_another_community_cannot_manage_activity(): void
    {
        [$community, $manager] = $this->community('owner');
        [$otherCommunity, $otherManager] = $this->community('other');
        $member = $this->youth();
        $this->membership($community, $member, OrganizationMembership::ROLE_MEMBER, $manager);
        $activity = $this->activity($community, $manager, $this->activityCategory());
        $this->actingAs($member)->get(route('manager.activities.show', [$community, $activity]))->assertForbidden();
        $this->actingAs($otherManager)->get(route('manager.activities.show', [$community, $activity]))->assertForbidden();
        $this->actingAs($manager)->get(route('manager.activities.show', [$otherCommunity, $activity]))->assertNotFound();
    }

    public function test_submit_changes_status_and_locks_editing(): void
    {
        [$community, $manager] = $this->community('submit');
        $category = $this->activityCategory();
        $activity = $this->activity($community, $manager, $category);
        $this->actingAs($manager)->post(route('manager.activities.submit', [$community, $activity]))->assertRedirect();
        $this->assertSame(Activity::REVIEW_PENDING, $activity->fresh()->review_status);
        $this->actingAs($manager)->get(route('manager.activities.edit', [$community, $activity]))->assertForbidden();
    }

    public function test_invalid_activity_input_is_rejected(): void
    {
        [$community, $manager] = $this->community('invalid');
        $category = $this->activityCategory();

        $this->actingAs($manager)->post(route('manager.activities.store', $community), $this->validData($category, [
            'title' => '',
            'location_type' => 'teleport',
            'end_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'start_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
        ]))->assertSessionHasErrors(['title', 'location_type', 'end_at']);
        $this->assertDatabaseCount('activities', 0);
    }

    public function test_revision_can_be_edited_and_resubmitted_by_contextual_manager(): void
    {
        [$community, $manager] = $this->community('revision');
        $category = $this->activityCategory();
        $activity = $this->activity($community, $manager, $category, Activity::REVIEW_REVISION);

        $this->actingAs($manager)->get(route('manager.activities.edit', [$community, $activity]))->assertOk();
        $this->actingAs($manager)->patch(route('manager.activities.update', [$community, $activity]), $this->validData($category, ['title' => 'Hasil Revisi']))->assertRedirect();
        $this->actingAs($manager)->post(route('manager.activities.submit', [$community, $activity]))->assertRedirect();
        $this->assertSame(Activity::REVIEW_PENDING, $activity->fresh()->review_status);
    }

    public function test_only_verifier_can_review_and_revision_or_rejection_requires_note(): void
    {
        [$community, $manager] = $this->community('review-auth');
        $activity = $this->activity($community, $manager, $this->activityCategory(), Activity::REVIEW_PENDING);
        $verifier = $this->userWithRole('verifier');
        $admin = $this->userWithRole('admin');
        $this->get(route('verifier.activity-verifications.index'))->assertRedirect();
        $this->actingAs($manager)->get(route('verifier.activity-verifications.index'))->assertForbidden();
        $this->actingAs($admin)->get(route('verifier.activity-verifications.index'))->assertForbidden();
        $this->actingAs($verifier)->get(route('verifier.activity-verifications.index'))->assertOk()->assertSee($activity->title);
        $this->actingAs($verifier)->post(route('verifier.activity-verifications.review', $activity), ['decision' => 'revision'])->assertSessionHasErrors('notes');
        $this->assertSame(Activity::REVIEW_PENDING, $activity->fresh()->review_status);
    }

    public function test_verifier_can_approve_revision_and_reject_and_notification_is_created(): void
    {
        [$community, $manager] = $this->community('decisions');
        $category = $this->activityCategory();
        $verifier = $this->userWithRole('verifier');
        foreach ([Activity::REVIEW_APPROVED, Activity::REVIEW_REVISION, Activity::REVIEW_REJECTED] as $decision) {
            $activity = $this->activity($community, $manager, $category, Activity::REVIEW_PENDING, $decision);
            $payload = ['decision' => $decision, 'notes' => $decision === Activity::REVIEW_APPROVED ? null : 'Alasan keputusan'];
            $this->actingAs($verifier)->post(route('verifier.activity-verifications.review', $activity), $payload)->assertRedirect();
            $this->assertSame($decision, $activity->fresh()->review_status);
            $this->assertDatabaseHas('activity_reviews', ['activity_id' => $activity->getKey(), 'decision' => $decision]);
        }
        $this->assertSame(3, $manager->siporaNotifications()->count());
    }

    public function test_only_approved_activity_can_be_published_and_publicly_viewed(): void
    {
        [$community, $manager] = $this->community('publish');
        $category = $this->activityCategory();
        $draft = $this->activity($community, $manager, $category);
        $approved = $this->activity($community, $manager, $category, Activity::REVIEW_APPROVED, 'approved-public');
        $rejected = $this->activity($community, $manager, $category, Activity::REVIEW_REJECTED, 'rejected-public');
        $this->get(route('activities.show', $approved))->assertNotFound();
        $this->actingAs($manager)->post(route('manager.activities.publish', [$community, $approved]))->assertRedirect();
        $this->get(route('activities.show', $approved))->assertOk()->assertSee($approved->title);
        $this->actingAs($manager)->post(route('manager.activities.publish', [$community, $rejected]))->assertForbidden();
        $this->get(route('activities.show', $draft))->assertNotFound();
        $this->actingAs($manager)->post(route('manager.activities.archive', [$community, $approved]))->assertRedirect();
        $this->get(route('activities.show', $approved))->assertNotFound();
    }

    public function test_phase_does_not_create_future_activity_domain_tables(): void
    {
        foreach (['activity_sessions', 'activity_registrations', 'participations', 'attendances', 'certificates'] as $table) {
            $this->assertFalse(Schema::hasTable($table));
        }
    }

    private function youth(): User
    {
        return $this->userWithRole('youth');
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    private function community(string $slug): array
    {
        $manager = $this->youth();
        $category = OrganizationCategory::create(['name' => 'Kategori '.$slug, 'slug' => 'category-'.$slug]);
        $community = Organization::create(['created_by_user_id' => $manager->getKey(), 'category_id' => $category->getKey(),
            'name' => 'Komunitas '.$slug, 'slug' => 'community-'.$slug, 'description' => 'Komunitas aktif', 'social_links' => (object) [],
            'review_status' => Organization::REVIEW_APPROVED, 'operational_status' => Organization::OPERATIONAL_ACTIVE, 'approved_at' => now()]);
        $this->membership($community, $manager, OrganizationMembership::ROLE_LEADER, $manager);

        return [$community, $manager];
    }

    private function membership(Organization $community, User $user, string $role, User $approver): void
    {
        OrganizationMembership::create(['organization_id' => $community->getKey(), 'user_id' => $user->getKey(),
            'access_role' => $role, 'membership_status' => OrganizationMembership::STATUS_ACTIVE,
            'requested_at' => now(), 'approved_at' => now(), 'approved_by' => $approver->getKey()]);
    }

    private function activityCategory(): ActivityCategory
    {
        return ActivityCategory::firstOrCreate(['slug' => 'kepemudaan'], ['name' => 'Kepemudaan']);
    }

    private function activity(Organization $community, User $creator, ActivityCategory $category, string $status = Activity::REVIEW_DRAFT, string $suffix = 'default'): Activity
    {
        return Activity::create(['organization_id' => $community->getKey(), 'category_id' => $category->getKey(), 'created_by_user_id' => $creator->getKey(),
            'title' => 'Activity '.$suffix, 'slug' => 'activity-'.$community->slug.'-'.$suffix, 'description' => 'Deskripsi Activity lengkap.',
            'location_type' => 'offline', 'venue_name' => 'Gedung Pemuda', 'start_at' => now()->addDays(5), 'end_at' => now()->addDays(5)->addHours(2),
            'registration_mode' => 'open', 'review_status' => $status, 'publication_status' => Activity::PUBLICATION_UNPUBLISHED,
            'execution_status' => Activity::EXECUTION_SCHEDULED]);
    }

    private function validData(ActivityCategory $category, array $override = []): array
    {
        return array_merge(['category_id' => $category->uuid(), 'title' => 'Workshop Pemuda', 'description' => 'Deskripsi Activity lengkap.',
            'location_type' => 'offline', 'venue_name' => 'Gedung Pemuda', 'start_at' => now()->addDays(7)->format('Y-m-d H:i:s'),
            'end_at' => now()->addDays(7)->addHours(2)->format('Y-m-d H:i:s'), 'registration_mode' => 'open'], $override);
    }
}
