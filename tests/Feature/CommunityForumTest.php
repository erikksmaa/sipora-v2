<?php

namespace Tests\Feature;

use App\Models\ForumCategory;
use App\Models\ForumReply;
use App\Models\ForumReport;
use App\Models\ForumThread;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommunityForumTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_public_read_and_complete_youth_only_write_with_safe_text(): void
    {
        $category = ForumCategory::create(['name' => 'Teknologi', 'slug' => 'teknologi']);
        $youth = User::factory()->create()->assignRole('youth');
        $other = User::factory()->create()->assignRole('youth');
        $data = ['category_id' => $category->uuid(), 'title' => 'Belajar Laravel bersama', 'body' => '<script>alert(1)</script>Bagaimana memulai Laravel dengan baik?'];
        $this->get(route('forum.index'))->assertOk();
        $this->post(route('forum.store'), $data)->assertRedirect(route('login'));
        $this->actingAs($youth)->post(route('forum.store'), $data)->assertRedirect(route('youth.onboarding'));
        $this->completeYouthOnboarding($youth);
        $this->post(route('forum.store'), $data)->assertRedirect();
        $thread = ForumThread::query()->firstOrFail();
        $this->assertStringNotContainsString('<script>', $thread->body);
        $this->get(route('forum.show', $thread))->assertOk()->assertSee('Belajar Laravel bersama')
            ->assertSee('Pemuda SIPORA #')->assertDontSee($youth->name);
        $this->get(route('search.index', ['q' => 'Belajar Laravel']))->assertOk()->assertSee('Belajar Laravel bersama');
        $this->post(route('forum.store'), [...$data, 'category_id' => '550e8400-e29b-41d4-a716-446655440000'])->assertNotFound();
        $this->actingAs($other)->put(route('forum.update', $thread), $data)->assertRedirect(route('youth.onboarding'));
        $this->completeYouthOnboarding($other);
        $this->put(route('forum.update', $thread), $data)->assertForbidden();
        $this->delete(route('forum.destroy', $thread))->assertForbidden();
    }

    public function test_replies_reports_helpful_and_admin_moderation_respect_visibility(): void
    {
        $category = ForumCategory::create(['name' => 'Pendidikan', 'slug' => 'pendidikan']);
        $author = User::factory()->create()->assignRole('youth');
        $reader = User::factory()->create()->assignRole('youth');
        $admin = User::factory()->create()->assignRole('admin');
        $this->completeYouthOnboarding($author);
        $this->completeYouthOnboarding($reader);
        $thread = ForumThread::create(['category_id' => $category->getKey(), 'author_id' => $author->getKey(), 'title' => 'Diskusi beasiswa', 'body' => 'Mari berbagi informasi beasiswa yang tersedia.']);
        $this->actingAs($reader)->post(route('forum.replies.store', $thread), ['body' => '<b>Informasi bantuan belajar</b>'])->assertRedirect();
        $reply = ForumReply::query()->firstOrFail();
        $this->assertSame('Informasi bantuan belajar', $reply->body);
        $this->assertDatabaseCount('notifications', 1);
        $this->post(route('forum.helpful', $thread))->assertRedirect();
        $this->assertDatabaseCount('forum_reactions', 1);
        $this->post(route('forum.helpful', $thread))->assertRedirect();
        $this->assertDatabaseCount('forum_reactions', 0);
        $this->post(route('forum.reports.store', $thread), ['reply_id' => $reply->uuid(), 'reason' => 'spam'])->assertRedirect();
        $this->post(route('forum.reports.store', $thread), ['reply_id' => '550e8400-e29b-41d4-a716-446655440000', 'reason' => 'spam'])->assertNotFound();
        $this->post(route('forum.reports.store', $thread), ['reply_id' => $reply->uuid(), 'reason' => 'spam'])->assertRedirect();
        $this->assertDatabaseCount('forum_reports', 1);
        $this->actingAs($author)->patch(route('admin.forum.replies.update', $reply), ['is_hidden' => 1])->assertForbidden();
        $this->actingAs($admin)->get(route('admin.forum.index'))->assertOk()->assertSee('Diskusi beasiswa');
        $this->patch(route('admin.forum.replies.update', $reply), ['is_hidden' => 1])->assertRedirect();
        $this->get(route('forum.show', $thread))->assertDontSee('Informasi bantuan belajar');
        $this->patch(route('admin.forum.threads.update', $thread), ['status' => 'locked'])->assertRedirect();
        $this->actingAs($reader)->post(route('forum.replies.store', $thread), ['body' => 'Balasan baru'])->assertForbidden();
        $this->actingAs($admin)->patch(route('admin.forum.threads.update', $thread), ['status' => 'hidden'])->assertRedirect();
        $this->get(route('forum.show', $thread))->assertNotFound();
        $report = ForumReport::query()->firstOrFail();
        $this->patch(route('admin.forum.reports.resolve', $report))->assertRedirect();
        $this->assertSame('resolved', $report->fresh()->status);
    }
}
