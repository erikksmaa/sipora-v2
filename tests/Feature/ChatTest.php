<?php

namespace Tests\Feature;

use App\Models\AuditEntry;
use App\Models\ChatMessage;
use App\Models\ChatMessageReport;
use App\Models\ChatRoom;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationMembership;
use App\Models\User;
use App\Services\ChatService;
use Database\Seeders\ChatRoomSeeder;
use Database\Seeders\DemoChatSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    private function user(string $role = 'youth'): User
    {
        return User::factory()->create()->assignRole($role)->fresh();
    }

    private function room(): ChatRoom
    {
        return app(ChatService::class)->globalRoom();
    }

    private function send(User $user, ChatRoom $room, string $text = 'Pesan pengujian'): array
    {
        return app(ChatService::class)->send($user, $room, $text, Str::uuid()->toString());
    }

    private function community(User $owner, string $slug = 'uji-chat'): Organization
    {
        $category = OrganizationCategory::firstOrCreate(['slug' => 'chat-test'], ['name' => 'Chat test']);
        $org = Organization::create(['created_by_user_id' => $owner->getKey(), 'category_id' => $category->getKey(), 'name' => 'Komunitas Uji', 'slug' => $slug, 'review_status' => 'approved', 'operational_status' => 'active', 'approved_at' => now()]);
        $this->member($org, $owner, 'leader');

        return $org;
    }

    private function member(Organization $org, User $user, string $role = 'member', string $status = 'active'): OrganizationMembership
    {
        return OrganizationMembership::create(['organization_id' => $org->getKey(), 'user_id' => $user->getKey(), 'access_role' => $role, 'membership_status' => $status, 'requested_at' => now(), 'approved_at' => $status === 'active' ? now() : null, 'ended_at' => in_array($status, ['left', 'removed']) ? now() : null]);
    }

    public function test_authenticated_roles_can_read_global_but_guests_and_unverified_cannot(): void
    {
        $this->get(route('chat.global'))->assertRedirect(route('login'));
        $this->getJson(route('chat.messages', $this->room()))->assertUnauthorized();
        foreach (['youth', 'verifier', 'admin'] as $role) {
            $this->actingAs($this->user($role))->get(route('chat.global'))->assertOk()->assertSee('Chat Global');
        }
        $unverified = $this->user();
        $unverified->forceFill(['email_verified_at' => null])->save();
        $this->actingAs($unverified)->get(route('chat.global'))->assertRedirect(route('verification.notice'));
    }

    public function test_valid_send_is_trimmed_idempotent_and_does_not_log_normal_messages(): void
    {
        $user = $this->user();
        $room = $this->room();
        $data = ['message' => '  Halo Pemalang!  ', 'nonce' => Str::uuid()->toString()];
        $this->actingAs($user)->postJson(route('chat.send', $room), $data)->assertOk()->assertJsonPath('message.message', 'Halo Pemalang!');
        $this->postJson(route('chat.send', $room), $data)->assertOk();
        $this->assertDatabaseCount('chat_messages', 1);
        $this->assertDatabaseCount('activity_log', 0);
    }

    public function test_invalid_messages_nonce_and_mass_assignment_are_rejected(): void
    {
        $room = $this->room();
        $this->actingAs($this->user());
        foreach (['', " \n\t ", str_repeat('a', 1501)] as $text) {
            $this->postJson(route('chat.send', $room), ['message' => $text, 'nonce' => Str::uuid()->toString()])->assertUnprocessable();
        }
        $this->postJson(route('chat.send', $room), ['message' => 'Halo', 'nonce' => 'invalid'])->assertUnprocessable();
        $this->postJson(route('chat.send', $room), ['message' => 'Halo', 'nonce' => Str::uuid()->toString(), 'user_id' => Str::uuid()->toString()])->assertUnprocessable();
        $this->assertDatabaseCount('chat_messages', 0);
    }

    public function test_ugc_is_text_not_executable_html_and_private_sender_fields_are_absent(): void
    {
        $author = $this->user();
        $room = $this->room();
        $text = '<script>alert("x")</script><img src=x onerror=alert(1)>';
        $this->send($author, $room, $text);
        $this->actingAs($this->user())->get(route('chat.room', $room))->assertOk()->assertDontSee($text, false)->assertDontSee($author->email);
        $row = $this->getJson(route('chat.messages', $room))->assertOk()->json('messages.0');
        $this->assertSame($text, $row['message']);
        $this->assertNull($row['profile_url']);
        $this->assertNull($row['avatar_url']);
        foreach (['email', 'user_id', 'password', 'roles', 'phone', 'birth_date', 'client_nonce'] as $field) {
            $this->assertArrayNotHasKey($field, $row);
        }
    }

    public function test_newer_poll_only_returns_new_changes_and_empty_poll_is_small(): void
    {
        $user = $this->user();
        $room = $this->room();
        $old = $this->send($user, $room, 'Lama');
        $this->actingAs($user);
        $initial = $this->getJson(route('chat.messages', $room))->assertOk()->json();
        $new = $this->send($user, $room, 'Baru');
        $delta = $this->getJson(route('chat.messages', $room).'?after='.urlencode($initial['cursor']))->assertOk()->json();
        $this->assertSame([$new['id']], array_column($delta['messages'], 'id'));
        $empty = $this->getJson(route('chat.messages', $room).'?after='.urlencode($delta['cursor']))->assertOk();
        $empty->assertJsonCount(0, 'messages');
        $this->assertLessThan(400, strlen($empty->getContent()));
    }

    public function test_history_is_bounded_oldest_first_without_overlap_and_bad_cursors_fail(): void
    {
        $user = $this->user();
        $room = $this->room();
        for ($i = 0; $i < 35; $i++) {
            $this->send($user, $room, 'Pesan '.$i);
        }
        $this->actingAs($user);
        $page = $this->getJson(route('chat.messages', $room))->assertOk()->json();
        $this->assertCount(30, $page['messages']);
        $this->assertSame('Pesan 5', $page['messages'][0]['message']);
        $older = $this->getJson(route('chat.messages', $room).'?before='.urlencode($page['before']))->assertOk()->json();
        $this->assertCount(5, $older['messages']);
        $this->assertFalse($older['has_more']);
        $this->assertSame('Pesan 0', $older['messages'][0]['message']);
        $this->assertEmpty(array_intersect(array_column($page['messages'], 'id'), array_column($older['messages'], 'id')));
        $this->getJson(route('chat.messages', $room).'?after=bad')->assertUnprocessable();
        $this->getJson(route('chat.messages', $room).'?after=bad&before=bad')->assertUnprocessable();
    }

    public function test_membership_roles_can_access_but_pending_removed_and_nonmembers_cannot(): void
    {
        $leader = $this->user();
        $org = $this->community($leader);
        $room = app(ChatService::class)->communityRoom($leader, $org);
        foreach (['leader', 'manager', 'member'] as $role) {
            $member = $role === 'leader' ? $leader : $this->user();
            if ($role !== 'leader') {
                $this->member($org, $member, $role);
            }
            $this->actingAs($member)->get(route('chat.community', $org))->assertOk();
            $this->postJson(route('chat.send', $room), ['message' => 'Halo', 'nonce' => Str::uuid()->toString()])->assertOk();
        }
        foreach (['pending', 'removed', 'left', 'rejected'] as $status) {
            $user = $this->user();
            $this->member($org, $user, 'member', $status);
            $this->actingAs($user)->getJson(route('chat.messages', $room))->assertForbidden();
            $this->postJson(route('chat.send', $room), ['message' => 'Tidak boleh', 'nonce' => Str::uuid()->toString()])->assertForbidden();
        }
        $this->actingAs($this->user())->get(route('chat.community', $org))->assertForbidden();
    }

    public function test_verifier_has_no_community_oversight_access_and_admin_is_read_only_moderator(): void
    {
        $leader = $this->user();
        $org = $this->community($leader);
        $room = app(ChatService::class)->communityRoom($leader, $org);
        $this->actingAs($this->user('verifier'))->getJson(route('chat.messages', $room))->assertForbidden();
        $this->actingAs($this->user('admin'))->getJson(route('chat.messages', $room))->assertOk()->assertJsonPath('can_send', false);
        $this->postJson(route('chat.send', $room), ['message' => 'Dilarang', 'nonce' => Str::uuid()->toString()])->assertForbidden();
    }

    public function test_cross_room_idor_and_inaccessible_reporting_are_blocked(): void
    {
        $leader = $this->user();
        $org = $this->community($leader);
        $room = app(ChatService::class)->communityRoom($leader, $org);
        $row = $this->send($leader, $room, 'Percakapan privat');
        $message = ChatMessage::firstOrFail();
        $outsider = $this->user();
        $this->actingAs($outsider)->getJson(route('chat.messages', $room))->assertForbidden();
        $this->postJson(route('chat.report', [$room, $message]), ['reason' => 'spam'])->assertForbidden();
        $this->postJson(route('chat.report', [$this->room(), $message]), ['reason' => 'spam'])->assertNotFound();
        $this->deleteJson(route('chat.remove', [$this->room(), $message]))->assertNotFound();
        $this->assertDatabaseCount('chat_message_reports', 0);
    }

    public function test_rooms_are_unique_and_unauthorized_lazy_creation_does_not_create_room(): void
    {
        $this->seed(ChatRoomSeeder::class);
        $this->seed(ChatRoomSeeder::class);
        $this->assertDatabaseCount('chat_rooms', 1);
        $leader = $this->user();
        $org = $this->community($leader);
        $this->actingAs($this->user())->get(route('chat.community', $org))->assertForbidden();
        $this->assertDatabaseCount('chat_rooms', 1);
        $this->actingAs($leader)->get(route('chat.community', $org))->assertOk();
        $this->get(route('chat.community', $org))->assertOk();
        $this->assertDatabaseCount('chat_rooms', 2);
        $this->expectException(UniqueConstraintViolationException::class);
        ChatRoom::create(['type' => 'global']);
    }

    public function test_duplicate_reports_are_safe_and_admin_can_action_with_audit(): void
    {
        $author = $this->user();
        $room = $this->room();
        $this->send($author, $room);
        $message = ChatMessage::firstOrFail();
        $this->actingAs($this->user())->postJson(route('chat.report', [$room, $message]), ['reason' => 'spam'])->assertOk();
        $this->postJson(route('chat.report', [$room, $message]), ['reason' => 'spam'])->assertOk();
        $this->assertDatabaseCount('chat_message_reports', 1);
        $report = ChatMessageReport::firstOrFail();
        $this->actingAs($this->user('admin'))->get(route('admin.chat.index'))->assertOk()->assertSee('Spam');
        $this->post(route('admin.chat.resolve', $report), ['decision' => 'actioned'])->assertRedirect();
        $this->assertSoftDeleted('chat_messages', ['id' => $message->getKey()]);
        $this->assertSame('actioned', $report->fresh()->status);
        $this->assertSame(1, AuditEntry::where('event', 'chat_message_deleted')->count());
        $this->assertStringNotContainsString('Pesan pengujian', AuditEntry::all()->toJson());
    }

    public function test_dismissal_retains_message_and_nonadmin_cannot_resolve(): void
    {
        $author = $this->user();
        $room = $this->room();
        $this->send($author, $room);
        $message = ChatMessage::firstOrFail();
        $reporter = $this->user();
        $this->actingAs($reporter)->postJson(route('chat.report', [$room, $message]), ['reason' => 'other'])->assertOk();
        $report = ChatMessageReport::firstOrFail();
        $this->post(route('admin.chat.resolve', $report), ['decision' => 'dismissed'])->assertForbidden();
        $this->actingAs($this->user('admin'))->post(route('admin.chat.resolve', $report), ['decision' => 'dismissed'])->assertRedirect();
        $this->assertSame('dismissed', $report->fresh()->status);
        $this->assertFalse($message->fresh()->trashed());
    }

    public function test_manager_moderates_only_own_community_not_other_room_or_global(): void
    {
        $leader = $this->user();
        $org = $this->community($leader);
        $room = app(ChatService::class)->communityRoom($leader, $org);
        $manager = $this->user();
        $this->member($org, $manager, 'manager');
        $this->send($leader, $room);
        $message = ChatMessage::firstOrFail();
        $this->actingAs($manager)->deleteJson(route('chat.remove', [$room, $message]))->assertOk();
        $global = $this->room();
        $row = $this->send($leader, $global);
        $msg = ChatMessage::where('chat_room_id', $global->getKey())->firstOrFail();
        $this->deleteJson(route('chat.remove', [$global, $msg]))->assertForbidden();
        $other = $this->user();
        $org2 = $this->community($other, 'autre');
        $room2 = app(ChatService::class)->communityRoom($other, $org2);
        $this->send($other, $room2);
        $msg2 = ChatMessage::where('chat_room_id', $room2->getKey())->firstOrFail();
        $this->deleteJson(route('chat.remove', [$room2, $msg2]))->assertForbidden();
    }

    public function test_deletion_is_propagated_to_poll_and_original_body_is_hidden(): void
    {
        $user = $this->user();
        $room = $this->room();
        $this->send($user, $room, 'Rahasia pesan lama');
        $msg = ChatMessage::firstOrFail();
        $this->actingAs($user);
        $initial = $this->getJson(route('chat.messages', $room))->json();
        $this->actingAs($this->user('admin'))->deleteJson(route('chat.remove', [$room, $msg]))->assertOk();
        $this->actingAs($user)->getJson(route('chat.messages', $room).'?after='.urlencode($initial['cursor']))->assertOk()->assertJsonPath('messages.0.deleted', true)->assertJsonPath('messages.0.message', null)->assertDontSee('Rahasia pesan lama');
        $this->get(route('chat.room', $room))->assertOk()->assertDontSee('Rahasia pesan lama');
    }

    public function test_admin_can_moderate_community_and_disable_room_without_hiding_history(): void
    {
        $leader = $this->user();
        $org = $this->community($leader);
        $room = app(ChatService::class)->communityRoom($leader, $org);
        $this->send($leader, $room);
        $message = ChatMessage::firstOrFail();
        $this->actingAs($this->user('admin'))->deleteJson(route('chat.remove', [$room, $message]))->assertOk();
        $this->patch(route('admin.chat.active', $room), ['active' => 0])->assertRedirect();
        $this->actingAs($leader)->getJson(route('chat.messages', $room))->assertOk()->assertJsonPath('can_send', false);
        $this->postJson(route('chat.send', $room), ['message' => 'Tidak boleh', 'nonce' => Str::uuid()->toString()])->assertForbidden();
        $this->patch(route('admin.chat.active', $room), ['active' => 1])->assertForbidden();
    }

    public function test_send_and_report_rate_limits_are_enforced(): void
    {
        $author = $this->user();
        $room = $this->room();
        $this->send($author, $room);
        $message = ChatMessage::firstOrFail();
        $user = $this->user();
        $this->actingAs($user);
        for ($i = 0; $i < 10; $i++) {
            $this->postJson(route('chat.report', [$room, $message]), ['reason' => 'spam'])->assertOk();
        }
        $this->postJson(route('chat.report', [$room, $message]), ['reason' => 'spam'])->assertTooManyRequests();
        // A fresh account has its own server-side limiter.
        $this->actingAs($this->user());
        for ($i = 0; $i < 30; $i++) {
            $this->postJson(route('chat.send', $room), ['message' => 'Pesan '.$i, 'nonce' => Str::uuid()->toString()])->assertOk();
        }
        $this->postJson(route('chat.send', $room), ['message' => 'Limit', 'nonce' => Str::uuid()->toString()])->assertTooManyRequests();
    }

    public function test_csrf_protection_still_applies_to_chat_posts(): void
    {
        $this->actingAs($this->user());
        $this->app['env'] = 'local';
        try {
            $this->postJson(route('chat.send', $this->room()), ['message' => 'No CSRF', 'nonce' => Str::uuid()->toString()])->assertStatus(419);
        } finally {
            $this->app['env'] = 'testing';
        }
    }

    public function test_removed_members_lose_poll_access_and_no_public_search_projection_exists(): void
    {
        $leader = $this->user();
        $org = $this->community($leader);
        $room = app(ChatService::class)->communityRoom($leader, $org);
        $member = $this->user();
        $membership = $this->member($org, $member);
        $this->send($member, $room, 'Private needle text');
        $this->actingAs($member)->getJson(route('chat.messages', $room))->assertOk();
        $membership->update(['membership_status' => 'removed']);
        $this->getJson(route('chat.messages', $room))->assertForbidden();
        $this->get('/search?q=needle')->assertOk()->assertDontSee('Private needle text')->assertDontSee($room->uuid());
    }

    public function test_eager_loading_query_count_does_not_grow_per_sender(): void
    {
        $room = $this->room();
        $reader = $this->user();
        for ($i = 0; $i < 12; $i++) {
            $this->send($this->user(),$room,'Hello');
        }
        DB::flushQueryLog();
        DB::enableQueryLog();
        app(ChatService::class)->listing($reader,$room);
        $count = count(DB::getQueryLog());
        DB::disableQueryLog();
        $this->assertLessThan(12,$count,'Sender/profile projections must be eager loaded.');
    }

    public function test_demo_chat_is_idempotent_and_has_no_plaintext_identity_data(): void
    {
        $user = $this->user();
        $user->update(['email' => 'youth1@sipora.test']);
        $org = $this->community($user,'komunitas-pemuda-digital-pemalang');
        $this->seed(DemoChatSeeder::class);
        $this->seed(DemoChatSeeder::class);
        $this->assertDatabaseCount('chat_rooms',2);
        $this->assertDatabaseCount('chat_messages',20);
    }
}
