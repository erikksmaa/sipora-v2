<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\Opportunity;
use App\Models\OpportunityCategory;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationMembership;
use App\Models\SocialComment;
use App\Models\SocialPost;
use App\Models\SocialReport;
use App\Models\User;
use App\Services\SocialFeedService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SocialFeedTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    private function youth(): User
    {
        $u = User::factory()->create()->assignRole('youth');
        $this->completeYouthOnboarding($u);

        return $u;
    }

    public function test_like_json_updates_are_idempotent_and_standard_form_still_redirects(): void
    {
        $user = $this->youth();
        $post = $this->makePost($user);
        $this->actingAs($user);
        $this->postJson(route('social.like', $post), ['liked' => true])->assertOk()->assertExactJson(['liked' => true, 'likes_count' => 1]);
        $this->postJson(route('social.like', $post), ['liked' => true])->assertOk()->assertExactJson(['liked' => true, 'likes_count' => 1]);
        $this->postJson(route('social.like', $post), ['liked' => false])->assertOk()->assertExactJson(['liked' => false, 'likes_count' => 0]);
        $this->post(route('social.like', $post), ['liked' => true])->assertRedirect();
        $this->postJson(route('social.like', $post), ['liked' => 'invalid'])->assertUnprocessable();
        $post->update(['visibility' => 'private']);
        $this->postJson(route('social.like', $post), ['liked' => true])->assertForbidden();
    }

    private function makePost(User $u, array $overrides = []): SocialPost
    {
        return SocialPost::create([...['author_id' => $u->getKey(), 'body' => 'Kabar kegiatan pemuda', 'visibility' => 'public'], ...$overrides]);
    }

    private function community(User $u): Organization
    {
        $c = OrganizationCategory::create(['name' => 'Digital', 'slug' => 'digital']);

        return Organization::create(['created_by_user_id' => $u->getKey(), 'category_id' => $c->getKey(), 'name' => 'Komunitas Digital', 'slug' => 'komunitas-digital', 'review_status' => 'approved', 'operational_status' => 'active', 'approved_at' => now()->subDay()]);
    }

    public function test_gate_ownership_sanitization_and_private_projection(): void
    {
        $u = $this->youth();
        $other = $this->youth();
        $data = ['body' => '<script>alert(1)</script>Kabar terbaru', 'visibility' => 'public'];
        $this->get(route('social.index'))->assertOk();
        $this->post(route('social.store'), $data)->assertRedirect(route('login'));
        $incomplete = User::factory()->create()->assignRole('youth');
        $this->actingAs($incomplete)->post(route('social.store'), $data)->assertRedirect(route('youth.onboarding'));
        $this->actingAs($u)->post(route('social.store'), $data)->assertRedirect();
        $post = SocialPost::firstOrFail();
        $this->assertStringNotContainsString('<script>', $post->body);
        // A visitor must not see the author's private identity. The owner's
        // authenticated header legitimately displays their own account name.
        $this->app['auth']->forgetGuards();
        $this->get(route('social.show', $post))->assertOk()->assertDontSee($u->name)->assertDontSee($u->email)->assertSee('Pemuda SIPORA #');
        $this->actingAs($u)->put(route('social.update', $post), ['body' => '<b>Update baik</b>', 'visibility' => 'private'])->assertRedirect();
        $this->assertSame('Update baik', $post->fresh()->body);
        $this->get(route('social.index', ['tab' => 'mine']))->assertOk()->assertSee('Update baik');
        $this->actingAs($other)->get(route('social.show', $post))->assertNotFound();
        $this->get(route('social.index'))->assertDontSee('Update baik');
        $this->put(route('social.update', $post), $data)->assertForbidden();
        $this->delete(route('social.destroy', $post))->assertForbidden();
        $this->post(route('social.like', $post), ['liked' => 1])->assertNotFound();
        $this->actingAs($u)->delete(route('social.destroy', $post))->assertRedirect();
        $this->get(route('social.show', $post))->assertNotFound();
        $this->assertDatabaseCount('social_posts', 1);
    }

    public function test_contextual_community_manager_and_revoked_access(): void
    {
        $u = $this->youth();
        $other = $this->youth();
        $org = $this->community($u);
        $data = ['body' => 'Update resmi Community', 'visibility' => 'public', 'organization_id' => $org->slug];
        $this->actingAs($u)->post(route('social.store'), $data)->assertForbidden(); // Creator is not a contextual manager.
        $membership = OrganizationMembership::create(['organization_id' => $org->getKey(), 'user_id' => $u->getKey(), 'membership_status' => 'active', 'access_role' => 'manager', 'approved_at' => now(), 'approved_by' => $u->getKey()]);
        $this->get(route('manager.social.index', $org))->assertOk()->assertSee('Update Community')->assertSee('Update publik');
        $this->post(route('manager.social.store', $org), $data)->assertRedirect(route('manager.social.index', $org));
        $post = SocialPost::firstOrFail();
        $this->actingAs($other)->post(route('social.store'), $data)->assertForbidden();
        $this->put(route('social.update', $post), $data)->assertForbidden();
        $membership->update(['membership_status' => 'removed']);
        $this->actingAs($u)->put(route('social.update', $post), $data)->assertForbidden();
        $this->get(route('manager.social.index', $org))->assertForbidden();
        $org->update(['operational_status' => 'inactive']);
        $this->get(route('social.show', $post))->assertNotFound();
        $this->get(route('social.index'))->assertDontSee('Update resmi Community');
    }

    public function test_safe_image_access_validation_and_cleanup(): void
    {
        Storage::fake('private');
        $u = $this->youth();
        $this->actingAs($u)->post(route('social.store'), ['body' => 'Gambar kegiatan', 'visibility' => 'public', 'image' => UploadedFile::fake()->image('photo.jpg')])->assertRedirect();
        $post = SocialPost::firstOrFail();
        $old = $post->image_path;
        Storage::disk('private')->assertExists($old);
        $this->get(route('social.image', $post))->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->post(route('social.store'), ['body' => 'Unsafe', 'visibility' => 'public', 'image' => UploadedFile::fake()->create('x.svg', 1, 'image/svg+xml')])->assertSessionHasErrors('image');
        $this->put(route('social.update', $post), ['body' => 'Foto privat', 'visibility' => 'private'])->assertRedirect();
        $this->actingAs($this->youth())->get(route('social.image', $post))->assertNotFound();
        $this->actingAs($u)->put(route('social.update', $post), ['body' => 'Tanpa foto', 'visibility' => 'public', 'remove_image' => 1])->assertRedirect();
        Storage::disk('private')->assertMissing($old);
        $this->get(route('social.image', $post))->assertNotFound();
    }

    public function test_follows_are_idempotent_and_following_is_chronological_privacy_safe(): void
    {
        $author = $this->youth();
        $reader = $this->youth();
        $org = $this->community($author);
        $old = $this->makePost($author, ['body' => 'Update Youth lama', 'created_at' => now()->subHours(2)]);
        $communityPost = $this->makePost($author, ['organization_id' => $org->getKey(), 'body' => 'Update Community baru']);
        $this->makePost($author, ['body' => 'Rahasia', 'visibility' => 'private']);
        $this->makePost($reader, ['body' => 'Tidak diikuti']);
        $slug = $author->profile->public_slug;
        $this->actingAs($reader)->post(route('social.follow'), ['target_type' => 'youth', 'target_id' => $slug, 'following' => 1])->assertNotFound();
        $author->profileVisibility()->create(['is_profile_public' => true]);
        $this->post(route('social.follow'), ['target_type' => 'youth', 'target_id' => $slug, 'following' => 1])->assertRedirect();
        $this->post(route('social.follow'), ['target_type' => 'youth', 'target_id' => $slug, 'following' => 1])->assertRedirect();
        $this->assertDatabaseCount('social_follows', 1);
        $this->assertDatabaseCount('notifications', 1);
        $this->post(route('social.follow'), ['target_type' => 'community', 'target_id' => $org->slug, 'following' => 1])->assertRedirect();
        $this->get(route('social.index', ['tab' => 'following']))->assertOk()->assertSeeInOrder(['Update Community baru', 'Update Youth lama'])->assertDontSee('Rahasia')->assertDontSee('Tidak diikuti');
        $this->post(route('social.follow'), ['target_type' => 'youth', 'target_id' => $reader->profile->public_slug, 'following' => 1])->assertNotFound();
        $author->profileVisibility()->update(['is_profile_public' => false]);
        $this->get(route('social.index', ['tab' => 'following']))->assertDontSee('Update Youth lama')->assertSee('Update Community baru');
        $this->post(route('social.follow'), ['target_type' => 'youth', 'target_id' => $slug, 'following' => 0])->assertRedirect();
        $this->assertDatabaseCount('social_follows', 1);
        $this->post(route('social.follow'), ['target_type' => 'community', 'target_id' => $org->slug, 'following' => 0])->assertRedirect();
        $this->get(route('social.index', ['tab' => 'following']))->assertDontSee('Update Community baru');
    }

    public function test_interactions_notifications_reporting_and_moderation(): void
    {
        $author = $this->youth();
        $reader = $this->youth();
        $post = $this->makePost($author);
        $another = $this->makePost($reader);
        $admin = User::factory()->create()->assignRole('admin');
        $this->actingAs($reader)->post(route('social.like', $post), ['liked' => 1])->assertRedirect();
        $this->post(route('social.like', $post), ['liked' => 1])->assertRedirect();
        $this->assertDatabaseCount('social_likes', 1);
        $this->post(route('social.like', $post), ['liked' => 0])->assertRedirect();
        $this->assertDatabaseCount('social_likes', 0);
        $this->post(route('social.comments.store', $post), ['body' => '<b>Keren sekali</b>'])->assertRedirect();
        $comment = SocialComment::firstOrFail();
        $this->assertSame('Keren sekali', $comment->body);
        $this->assertDatabaseCount('notifications', 1);
        $this->post(route('social.reports.store', $another), ['comment_id' => $comment->uuid(), 'reason' => 'spam'])->assertNotFound();
        $this->post(route('social.reports.store', $post), ['comment_id' => '550e8400-e29b-41d4-a716-446655440000', 'reason' => 'spam'])->assertNotFound();
        $reportData = ['comment_id' => $comment->uuid(), 'reason' => 'spam', 'details' => '<b>Keterangan</b>'];
        $this->post(route('social.reports.store', $post), $reportData)->assertRedirect();
        $this->post(route('social.reports.store', $post), $reportData)->assertRedirect();
        $this->assertDatabaseCount('social_reports', 1);
        $this->patch(route('admin.social.comments.update', $comment), ['is_hidden' => 1])->assertForbidden();
        $this->actingAs($admin)->get(route('admin.social.index'))->assertOk()->assertSee('Keren sekali');
        $this->patch(route('admin.social.comments.update', $comment), ['is_hidden' => 1])->assertRedirect();
        $this->get(route('social.show', $post))->assertDontSee('Keren sekali');
        $this->patch(route('admin.social.posts.update', $post), ['is_hidden' => 1])->assertRedirect();
        $this->get(route('social.show', $post))->assertNotFound();
        $this->actingAs($author)->put(route('social.update', $post), ['body' => 'Bypass moderation', 'visibility' => 'public'])->assertForbidden();
        $this->actingAs($admin)->patch(route('admin.social.posts.update', $post), ['is_hidden' => 0])->assertRedirect();
        $this->patch(route('admin.social.comments.update', $comment), ['is_hidden' => 0])->assertRedirect();
        $this->get(route('social.show', $post))->assertOk()->assertSee('Keren sekali');
        $report = SocialReport::firstOrFail();
        $this->patch(route('admin.social.reports.resolve', $report))->assertRedirect();
        $this->assertSame('resolved', $report->fresh()->status);
    }

    public function test_derived_ecosystem_filters_pagination_and_bounded_queries(): void
    {
        $u = $this->youth();
        $org = $this->community($u);
        $category = ActivityCategory::create(['name' => 'Teknologi', 'slug' => 'teknologi']);
        $activity = Activity::create(['organization_id' => $org->getKey(), 'category_id' => $category->getKey(), 'created_by_user_id' => $u->getKey(), 'title' => 'Workshop terbuka', 'slug' => 'workshop-terbuka', 'description' => 'Belajar bersama', 'location_type' => 'offline', 'start_at' => now()->addWeek(), 'end_at' => now()->addWeek()->addHours(2), 'registration_mode' => 'open', 'review_status' => 'approved', 'publication_status' => 'published', 'execution_status' => 'scheduled', 'published_at' => now()->subHour()]);
        $oc = OpportunityCategory::create(['name' => 'Magang', 'slug' => 'magang']);
        $op = Opportunity::create(['category_id' => $oc->getKey(), 'organization_id' => $org->getKey(), 'created_by_user_id' => $u->getKey(), 'title' => 'Magang terbuka', 'slug' => 'magang-terbuka', 'description' => 'Kesempatan', 'provider_name' => 'Mitra', 'external_url' => 'https://example.test', 'publication_status' => 'published', 'published_at' => now()->subMinute(), 'deadline_at' => now()->addWeek()]);
        $this->get(route('social.index'))->assertOk()->assertSee('Workshop terbuka')->assertSee('Magang terbuka')->assertSee('Komunitas Digital');
        $this->get(route('social.index', ['tab' => 'activity']))->assertSee('Workshop terbuka')->assertDontSee('Magang terbuka');
        $this->get(route('social.index', ['tab' => 'opportunity']))->assertSee('Magang terbuka')->assertDontSee('Workshop terbuka');
        $op->update(['deadline_at' => now()->subDay()]);
        $activity->update(['publication_status' => 'unpublished']);
        $this->get(route('social.index'))->assertDontSee('Workshop terbuka')->assertDontSee('Magang terbuka');
        $smallCount = 0;
        $measuring = false;
        DB::listen(function () use (&$smallCount, &$measuring) {
            if ($measuring) {
                $smallCount++;
            }
        });
        $this->makePost($u);
        $measuring = true;
        app(SocialFeedService::class)->paginate('all', null);
        $measuring = false;
        $first = $smallCount;
        for ($i = 0; $i < 20; $i++) {
            $this->makePost($u, ['body' => 'Post pagination '.$i]);
        }
        $smallCount = 0;
        $measuring = true;
        $page = app(SocialFeedService::class)->paginate('all', null);
        $measuring = false;
        $this->assertSame(12, $page->count());
        $this->assertLessThanOrEqual($first + 1,$smallCount);
        $this->assertGreaterThan(12,$page->total());
        $this->get(route('social.index',['page' => 2]))->assertOk();
    }
}
