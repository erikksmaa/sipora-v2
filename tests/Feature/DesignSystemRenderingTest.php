<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DesignSystemRenderingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_public_navigation_and_hero_render_the_shared_visual_foundation(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('public-nav-active', false)
            ->assertSee('Plus+Jakarta+Sans', false)
            ->assertSee('Pixelify+Sans', false)
            ->assertSee('Press+Start+2P', false)
            ->assertSee('JetBrains+Mono', false)
            ->assertSee('bg-primary-800', false)
            ->assertSee('px-footer px-zig-mountain', false)
            ->assertSee('Temukan ruang untuk');
    }

    public function test_admin_dashboard_renders_the_shared_workspace_shell(): void
    {
        $admin = User::factory()->create()->assignRole('admin');

        $this->actingAs($admin)->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('workspaceShell', false)
            ->assertSee('workspace-stage', false)
            ->assertSee('Dashboard Tata Kelola SIPORA')
            ->assertSee('aria-label="Breadcrumb"', false)
            ->assertSee('Ciutkan sidebar')
            ->assertSee('Navigasi tanpa JavaScript');
    }

    public function test_verifier_dashboard_uses_the_same_shell_with_role_specific_navigation(): void
    {
        $verifier = User::factory()->create()->assignRole('verifier');

        $this->actingAs($verifier)->get(route('verifier.dashboard'))
            ->assertOk()
            ->assertSee('workspaceShell', false)
            ->assertSee('workspace-stage', false)
            ->assertSee('Dashboard Pengawasan &amp; Kurasi', false)
            ->assertSee('E-LPJ Keuangan')
            ->assertDontSee('Identitas Pemuda');
    }

    public function test_workspace_authorization_remains_role_scoped(): void
    {
        $admin = User::factory()->create()->assignRole('admin');
        $verifier = User::factory()->create()->assignRole('verifier');

        $this->actingAs($admin)->get(route('verifier.dashboard'))->assertForbidden();
        $this->actingAs($verifier)->get(route('admin.dashboard'))->assertForbidden();
    }
}
