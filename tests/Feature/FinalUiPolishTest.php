<?php

namespace Tests\Feature;

use App\Models\SocialPost;
use App\Models\SocialReport;
use App\Models\User;
use App\Services\AdminQueueSummaryService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class FinalUiPolishTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_navigation_password_and_local_google_icon(): void
    {
        config(['services.google.client_id' => 'local-test', 'services.google.client_secret' => 'local-test']);
        foreach (['/login', '/register'] as $url) {
            $this->get($url)->assertOk()->assertSee('Navigasi utama mobile')->assertSee('password-toggle')->assertSee('Tampilkan kata sandi')->assertSee('fill="#4285F4"', false)->assertDontSee('fc-launcher');
        }
        $this->get('/login')->assertSee('Daftar sekarang')->assertSee('Ingat saya')->assertSee('Lupa kata sandi?')->assertDontSee('Sign in');
    }

    public function test_admin_queues_count_pending_reports_and_hide_zero_badges(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $admin = User::factory()->create()->assignRole('admin');
        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk()->assertDontSee('data-queue-indicator=', false)->assertSee('fc-launcher')->assertSee('Moderasi Chat')->assertDontSee('>Chat Global<', false);
        $reporter = User::factory()->create();
        $post = SocialPost::create(['author_id' => $reporter->getKey(), 'body' => 'Kabar uji', 'visibility' => 'public']);
        SocialReport::create(['post_id' => $post->getKey(), 'reporter_id' => $reporter->getKey(), 'reason' => 'spam', 'status' => 'pending']);
        SocialReport::create(['post_id' => $post->getKey(), 'reporter_id' => $reporter->getKey(), 'reason' => 'spam', 'status' => 'resolved']);
        $this->assertSame(1, app(AdminQueueSummaryService::class)->navigation()[route('admin.social.index')]);
        $this->get(route('admin.dashboard'))->assertOk()->assertSee('data-queue-indicator="'.route('admin.social.index').'"', false)->assertSee('1 perlu ditindaklanjuti');
        $this->app->instance(AdminQueueSummaryService::class, new class
        {
            public function navigation(): array
            {
                return [route('admin.social.index') => 100];
            }
        });
        $this->get(route('admin.dashboard'))->assertOk()->assertSee('99+')->assertSee('100 perlu ditindaklanjuti')->assertSee('x-show="collapsed"', false);
    }

    public function test_youth_and_verifier_keep_floating_chat_without_global_sidebar_entry(): void
    {
        $this->seed(RolePermissionSeeder::class);
        foreach (['youth' => 'youth.home', 'verifier' => 'verifier.dashboard'] as $role => $route) {
            $user = User::factory()->create()->assignRole($role);
            if ($role === 'youth') {
                $this->completeYouthOnboarding($user);
            }
            $this->actingAs($user)->get(route($route))->assertOk()->assertSee('fc-launcher')->assertDontSee('>Chat Global<', false);
        }
    }

    public function test_theme_pagination_preserves_page_links_and_current_state(): void
    {
        $paginator = new LengthAwarePaginator([], 100, 10, 2, ['path' => '/feed']);
        $html = (string) $paginator->links();
        $this->assertStringContainsString('sipora-pagination', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
        $this->assertStringContainsString('/feed?page=3', $html);
        $this->assertStringNotContainsString('bg-black', $html);
    }
}
