<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserNotification;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationCenterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_user_sees_only_own_notifications_and_can_mark_one_or_all_read(): void
    {
        $user = $this->youth();
        $other = $this->youth();
        $first = $this->notice($user, 'Notifikasi milik sendiri');
        $second = $this->notice($user, 'Notifikasi kedua');
        $foreign = $this->notice($other, 'Notifikasi rahasia pengguna lain');

        $this->actingAs($user)->get(route('notifications.index'))->assertOk()->assertSee($first->title)->assertDontSee($foreign->title);
        $this->actingAs($user)->patch(route('notifications.read', $first))->assertRedirect();
        $this->assertNotNull($first->fresh()->read_at);
        $this->actingAs($user)->patch(route('notifications.read', $foreign))->assertNotFound();
        $this->actingAs($user)->patch(route('notifications.read-all'))->assertRedirect();
        $this->assertNotNull($second->fresh()->read_at);
        $this->assertNull($foreign->fresh()->read_at);
    }

    public function test_untrusted_notification_url_is_never_rendered_as_an_action(): void
    {
        $user = $this->youth();
        $this->notice($user, 'Tautan tidak dipercaya', ['url' => 'https://evil.test/steal']);

        $this->actingAs($user)->get(route('notifications.index'))->assertOk()->assertDontSee('https://evil.test', false);
    }

    private function youth(): User
    {
        $user = User::factory()->create();
        $user->assignRole('youth');

        return $user;
    }

    private function notice(User $user, string $title, array $data = []): UserNotification
    {
        return UserNotification::create(['user_id' => $user->getKey(), 'notification_type' => 'untrusted_test', 'title' => $title, 'body' => 'Isi notifikasi aman.', 'data' => (object) $data]);
    }
}
