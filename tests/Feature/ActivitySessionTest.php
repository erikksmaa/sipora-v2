<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\ActivitySession;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationMembership;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ActivitySessionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_manager_can_create_and_edit_session_for_own_activity(): void
    {
        [$community, $manager, $activity] = $this->activityContext('own');
        $this->actingAs($manager)->get(route('manager.activities.sessions.index', [$community, $activity]))->assertOk()->assertSee('Belum ada sesi');
        $this->actingAs($manager)->post(route('manager.activities.sessions.store', [$community, $activity]), $this->validData())->assertRedirect();
        $session = ActivitySession::firstOrFail();
        $this->assertSame(16, strlen($session->getKey()));
        $this->assertTrue($session->activity->is($activity));
        $this->actingAs($manager)->patch(route('manager.activities.sessions.update', [$community, $activity, $session]), $this->validData(['title' => 'Sesi Diperbarui']))->assertRedirect();
        $this->assertSame('Sesi Diperbarui', $session->fresh()->title);
        $second = $activity->sessions()->create($this->validData(['session_number' => 2, 'title' => 'Sesi Kedua']));
        $this->actingAs($manager)->patch(route('manager.activities.sessions.move', [$community, $activity, $second]), ['direction' => 'up'])->assertRedirect();
        $this->assertSame(1, $second->fresh()->session_number);
        $this->assertSame(2, $session->fresh()->session_number);
    }

    public function test_cross_community_member_and_outsider_access_is_denied(): void
    {
        [$community, $manager, $activity] = $this->activityContext('protected');
        [$otherCommunity, $otherManager] = $this->activityContext('other');
        $member = $this->youth();
        $this->membership($community, $member, OrganizationMembership::ROLE_MEMBER, $manager);

        $this->actingAs($otherManager)->post(route('manager.activities.sessions.store', [$community, $activity]), $this->validData())->assertForbidden();
        $this->actingAs($member)->post(route('manager.activities.sessions.store', [$community, $activity]), $this->validData())->assertForbidden();
        $this->actingAs($manager)->get(route('manager.activities.sessions.index', [$otherCommunity, $activity]))->assertNotFound();
    }

    public function test_invalid_schedule_and_duplicate_session_number_are_rejected(): void
    {
        [$community, $manager, $activity] = $this->activityContext('validation');
        $this->actingAs($manager)->post(route('manager.activities.sessions.store', [$community, $activity]), $this->validData())->assertRedirect();
        $this->actingAs($manager)->post(route('manager.activities.sessions.store', [$community, $activity]), $this->validData(['title' => 'Duplikat']))->assertSessionHasErrors('session_number');
        $this->actingAs($manager)->post(route('manager.activities.sessions.store', [$community, $activity]), $this->validData([
            'session_number' => 2,
            'start_at' => now()->addDays(4)->format('Y-m-d H:i:s'),
            'end_at' => now()->addDays(3)->format('Y-m-d H:i:s'),
        ]))->assertSessionHasErrors('end_at');
        $this->assertDatabaseCount('activity_sessions', 1);
    }

    public function test_pending_and_published_activity_sessions_are_locked_from_changes(): void
    {
        [$community, $manager, $pending] = $this->activityContext('pending', Activity::REVIEW_PENDING);
        $this->actingAs($manager)->post(route('manager.activities.sessions.store', [$community, $pending]), $this->validData())->assertForbidden();

        [$publishedCommunity, $publishedManager, $published] = $this->activityContext('published', Activity::REVIEW_APPROVED, Activity::PUBLICATION_PUBLISHED);
        $this->actingAs($publishedManager)->post(route('manager.activities.sessions.store', [$publishedCommunity, $published]), $this->validData())->assertForbidden();
    }

    public function test_public_published_activity_shows_sessions_while_unpublished_activity_is_inaccessible(): void
    {
        [$community, $manager, $published] = $this->activityContext('public', Activity::REVIEW_APPROVED, Activity::PUBLICATION_PUBLISHED);
        $published->sessions()->create($this->validData(['title' => 'Pembukaan Publik']));
        $this->get(route('activities.show', $published))->assertOk()->assertSee('Pembukaan Publik')->assertSee('Sesi Activity');

        [$privateCommunity, $privateManager, $unpublished] = $this->activityContext('private', Activity::REVIEW_APPROVED);
        $unpublished->sessions()->create($this->validData(['title' => 'Sesi Rahasia']));
        $this->get(route('activities.show', $unpublished))->assertNotFound()->assertDontSee('Sesi Rahasia');
    }

    public function test_activity_session_table_exists(): void
    {
        $this->assertTrue(Schema::hasTable('activity_sessions'));
    }

    private function activityContext(string $slug, string $review = Activity::REVIEW_DRAFT, string $publication = Activity::PUBLICATION_UNPUBLISHED): array
    {
        $manager = $this->youth();
        $organizationCategory = OrganizationCategory::create(['name' => 'Kategori '.$slug, 'slug' => 'organization-'.$slug]);
        $community = Organization::create(['created_by_user_id' => $manager->getKey(), 'category_id' => $organizationCategory->getKey(),
            'name' => 'Komunitas '.$slug, 'slug' => 'community-'.$slug, 'description' => 'Komunitas aktif', 'social_links' => (object) [],
            'review_status' => Organization::REVIEW_APPROVED, 'operational_status' => Organization::OPERATIONAL_ACTIVE, 'approved_at' => now()]);
        $this->membership($community, $manager, OrganizationMembership::ROLE_LEADER, $manager);
        $category = ActivityCategory::firstOrCreate(['slug' => 'session-category'], ['name' => 'Kategori Sesi']);
        $activity = Activity::create(['organization_id' => $community->getKey(), 'category_id' => $category->getKey(), 'created_by_user_id' => $manager->getKey(),
            'title' => 'Activity '.$slug, 'slug' => 'activity-'.$slug, 'description' => 'Activity dengan sesi.', 'location_type' => 'offline',
            'venue_name' => 'Gedung Pemuda', 'start_at' => now()->addDays(3), 'end_at' => now()->addDays(4), 'registration_mode' => 'open',
            'review_status' => $review, 'publication_status' => $publication, 'execution_status' => Activity::EXECUTION_SCHEDULED,
            'published_at' => $publication === Activity::PUBLICATION_PUBLISHED ? now() : null]);

        return [$community, $manager, $activity];
    }

    private function youth(): User
    {
        $user = User::factory()->create();
        $user->assignRole('youth');

        return $user;
    }

    private function membership(Organization $community, User $user, string $role, User $approver): void
    {
        OrganizationMembership::create(['organization_id' => $community->getKey(), 'user_id' => $user->getKey(),
            'access_role' => $role, 'membership_status' => OrganizationMembership::STATUS_ACTIVE, 'requested_at' => now(),
            'approved_at' => now(), 'approved_by' => $approver->getKey()]);
    }

    private function validData(array $override = []): array
    {
        return array_merge(['session_number' => 1, 'title' => 'Pembukaan', 'description' => 'Deskripsi sesi',
            'start_at' => now()->addDays(3)->format('Y-m-d H:i:s'), 'end_at' => now()->addDays(3)->addHours(2)->format('Y-m-d H:i:s'),
            'venue_name' => 'Gedung Pemuda', 'address_text' => 'Pemalang', 'notes' => 'Catatan pengelola'], $override);
    }
}
