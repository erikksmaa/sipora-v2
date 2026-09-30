<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_admin_sees_factual_aggregate_dashboard_without_ranking(): void
    {
        $admin = $this->roleUser('admin');
        $this->roleUser('youth');

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk()->assertSee('Ringkasan faktual')->assertSee('Youth terdaftar')->assertDontSee('Peringkat Pemuda')->assertDontSee('Skor Pemuda');
        $this->actingAs($this->roleUser('youth'))->get(route('admin.dashboard'))->assertForbidden();
        $this->app['auth']->logout();
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_verifier_dashboard_exposes_only_operational_queue_counts(): void
    {
        $verifier = $this->roleUser('verifier');
        $this->actingAs($verifier)->get(route('verifier.dashboard'))->assertOk()->assertSee('Ringkasan antrean')->assertSee('Proposal Program')->assertDontSee('Peringkat Pemuda');
    }

    private function roleUser(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }
}
