<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class YouthWorkspaceRenderingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_youth_workspace_exposes_clear_personal_navigation_and_explore_switch(): void
    {
        $youth = User::factory()->create()->assignRole('youth');

        $this->actingAs($youth)->get(route('youth.home'))
            ->assertOk()
            ->assertSee('Ruang Saya')
            ->assertSee('Profil Saya')
            ->assertSee('Pendaftaran Saya')
            ->assertSee('Activity Passport')
            ->assertSee('Community Saya')
            ->assertSee('Tersimpan')
            ->assertSee('Notifikasi')
            ->assertSee('Jelajahi SIPORA')
            ->assertSee('aria-current="page"', false)
            ->assertSee('x-data="workspaceShell"', false)
            ->assertSee(':inert="mobileOpen"', false)
            ->assertSee('<main id="main" tabindex="-1"', false);
    }

    public function test_personal_workspace_surfaces_render_inside_the_shared_youth_shell(): void
    {
        $youth = User::factory()->create()->assignRole('youth');

        $routes = [
            'youth.profile.show' => 'Profil Saya',
            'youth.profile.privacy' => 'Kontrol Privasi',
            'youth.activities.index' => 'Pendaftaran Saya',
            'youth.passport.index' => 'Activity Passport',
            'youth.certificates.index' => 'Sertifikat Activity',
            'youth.communities.index' => 'Community Saya',
            'youth.opportunities.bookmarks' => 'Opportunity tersimpan',
            'notifications.index' => 'Notifikasi',
        ];

        foreach ($routes as $route => $heading) {
            $this->actingAs($youth)->get(route($route))
                ->assertOk()
                ->assertSee($heading)
                ->assertSee('Navigasi Youth');
        }
    }

    public function test_new_personal_aggregation_routes_keep_existing_workspace_authorization(): void
    {
        $admin = User::factory()->create()->assignRole('admin');

        $urls = [route('youth.activities.index'), route('youth.profile.privacy')];

        foreach ($urls as $url) {
            $this->get($url)->assertRedirectToRoute('login');
        }

        foreach ($urls as $url) {
            $this->actingAs($admin)->get($url)->assertForbidden();
        }
    }
}
