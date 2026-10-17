<?php

namespace Tests\Unit;

use App\Models\{Organization, User, UserProfile, UserProfileVisibility};
use App\Services\Community\ManagedCommunityService;
use App\Support\Media\UserAvatar;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\{Auth, Blade, Storage};
use Mockery;
use PHPUnit\Framework\TestCase;
use Spatie\Permission\Models\Role;

final class PublicNavigationTest extends TestCase
{
    private $app;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app = require __DIR__.'/../../bootstrap/app.php';
        $this->app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        config(['session.driver' => 'array', 'cache.default' => 'array']);
        $this->app['session']->put('_token', 'navigation-test-csrf');
        $this->app->bind(ManagedCommunityService::class, fn () => new class {
            public function forUser(User $user): Collection { return new Collection; }
        });
        $this->route('/');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        \Illuminate\View\Component::flushCache();
        \Illuminate\View\Component::forgetFactory();
        \Illuminate\View\Component::forgetComponentsResolver();
        \Illuminate\Foundation\Bootstrap\HandleExceptions::flushState($this);
        \Illuminate\Support\Facades\Facade::clearResolvedInstances();
        $this->app->flush();
        parent::tearDown();
    }

    private function route(string $path): void
    {
        $request = \Illuminate\Http\Request::create($path);
        $route = $this->app['router']->getRoutes()->match($request);
        $request->setRouteResolver(fn () => $route);
        $this->app->instance('request', $request);
        $this->app['url']->setRequest($request);
    }

    private function actor(string $role): User
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->name = 'Erik Kusuma Rais';
        $user->setRelation('roles', collect([new Role(['name' => $role, 'guard_name' => 'web'])]));
        $user->setRelation('profile', null);
        $user->setRelation('profileVisibility', null);
        // Gate's Spatie before-hook must not query a real permissions table.
        $user->shouldReceive('checkPermissionTo')->andReturn(false);
        $notifications = Mockery::mock(HasMany::class);
        $notifications->shouldReceive('whereNull')->with('read_at')->andReturnSelf();
        $notifications->shouldReceive('count')->andReturn(0);
        $user->shouldReceive('siporaNotifications')->andReturn($notifications);
        Auth::setUser($user);
        return $user;
    }

    private function dom(string $html): \DOMXPath
    {
        $dom = new \DOMDocument;
        @$dom->loadHTML('<?xml encoding="utf-8" ?>'.$html);
        return new \DOMXPath($dom);
    }

    public function test_public_home_and_bottom_nav_mean_the_same_for_every_role(): void
    {
        foreach ([null, 'youth', 'admin', 'verifier'] as $role) {
            Auth::forgetGuards();
            if ($role) $this->actor($role);
            $html = Blade::render('<x-public.navbar /><x-mobile-bottom-nav />');
            $xpath = $this->dom($html);
            $links = $xpath->query('//a[normalize-space(.)="Beranda"]');
            $this->assertGreaterThan(0, $links->length);
            foreach ($links as $link) $this->assertSame(route('home'), $link->getAttribute('href'));
            $bottom = $xpath->query('//nav[@aria-label="Navigasi utama mobile"]/a');
            $this->assertCount(5, $bottom);
            $this->assertSame(['Beranda','Pemuda','Komunitas','Program','Forum'], array_map(fn($a) => trim($a->textContent), iterator_to_array($bottom)));
            $this->assertStringNotContainsString('>Profil<', $html);
            if (! $role) {
                $this->assertStringContainsString('Masuk</a>', $html);
                $this->assertStringNotContainsString('account-avatar', $html);
            } else {
                $this->assertStringContainsString('Akun Erik Kusuma Rais', $html);
                $this->assertStringContainsString('ER</span>', $html);
            }
        }
    }

    public function test_account_actions_are_role_specific_and_logout_is_csrf_post(): void
    {
        foreach (['youth' => 'Youth', 'admin' => 'Admin', 'verifier' => 'Verifier'] as $role => $label) {
            $this->actor($role);
            $html = Blade::render('<x-account-dropdown />');
            $this->assertStringContainsString('Buka '.$label.' Workspace', $html);
            foreach (['Youth','Admin','Verifier'] as $other) if ($other !== $label) $this->assertStringNotContainsString('Buka '.$other.' Workspace', $html);
            $this->assertStringContainsString('Kembali ke Situs Publik', $html);
            $xpath = $this->dom($html);
            $form = $xpath->query('//form')->item(0);
            $this->assertSame('post', $form->getAttribute('method'));
            $this->assertSame(route('logout'), $form->getAttribute('action'));
            $this->assertSame('navigation-test-csrf', $xpath->query('//form/input[@name="_token"]')->item(0)->getAttribute('value'));
        }
    }

    public function test_contextual_workspace_uses_only_service_authorized_communities(): void
    {
        $this->actor('youth');
        $org = new Organization(['name' => 'Komunitas A', 'slug' => 'komunitas-a']);
        $this->app->bind(ManagedCommunityService::class, fn () => new class($org) {
            public function __construct(private Organization $organization) {}
            public function forUser(User $user): Collection { return new Collection([$this->organization]); }
        });
        $html = Blade::render('<x-account-dropdown />');
        $this->assertStringContainsString(route('manager.dashboard', $org), $html);
        $this->assertStringNotContainsString('Komunitas B', $html);
    }

    public function test_active_bottom_destination_is_unique_on_list_and_detail_routes(): void
    {
        foreach (['/' => 'Beranda', '/youth' => 'Pemuda', '/portfolio/example' => 'Pemuda', '/communities' => 'Komunitas', '/communities/example' => 'Komunitas', '/programs' => 'Program', '/programs/example' => 'Program', '/forum' => 'Forum', '/forum/example' => 'Forum', '/activities' => null, '/youth/home' => null] as $path => $expected) {
            $this->route($path);
            $xpath = $this->dom(Blade::render('<x-mobile-bottom-nav />'));
            $active = $xpath->query('//nav/a[@aria-current="page"]');
            $this->assertCount($expected ? 1 : 0, $active, $path);
            if ($expected) $this->assertSame($expected, trim($active->item(0)->textContent));
        }
        foreach (['workspace','base'] as $layout) $this->assertStringNotContainsString('<x-mobile-bottom-nav', file_get_contents(resource_path('views/layouts/'.$layout.'.blade.php')));
    }

    public function test_photo_uses_public_slug_and_keeps_private_or_missing_images_hidden(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('profile-test.jpg', 'fixture');
        $user = $this->actor('youth');
        $user->setRelation('profile', new UserProfile(['public_slug' => 'erik-demo', 'profile_photo_path' => 'profile-test.jpg']));
        $visibility = new UserProfileVisibility(['is_profile_public' => true, 'show_photo' => true]);
        $user->setRelation('profileVisibility', $visibility);
        $this->assertSame(route('portfolio.photo', 'erik-demo'), UserAvatar::publicUrl($user));
        $this->assertSame(route('portfolio.photo', 'erik-demo'), UserAvatar::accountUrl($user));
        $visibility->show_photo = false;
        $this->assertNull(UserAvatar::publicUrl($user));
        $this->assertSame(route('youth.profile.photo'), UserAvatar::accountUrl($user));
        $visibility->show_photo = true;
        $visibility->is_profile_public = false;
        $this->assertNull(UserAvatar::publicUrl($user));
        $visibility->is_profile_public = true;
        Storage::disk('public')->delete('profile-test.jpg');
        $this->assertNull(UserAvatar::publicUrl($user));
        $this->assertNull(UserAvatar::accountUrl($user));
        $this->assertSame('ER', UserAvatar::initials('  Erik Kusuma Rais  '));
        $this->assertSame('A', UserAvatar::initials('Aulia'));
    }

    public function test_chat_projection_links_use_the_public_slug_and_retain_privacy(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('chat-photo.jpg', 'fixture');
        $user = $this->actor('youth');
        $user->id = \App\Support\BinaryUuid::generate();
        $user->setRelation('profile', new UserProfile(['public_slug' => 'erik-chat', 'profile_photo_path' => 'chat-photo.jpg']));
        $user->setRelation('profileVisibility', new UserProfileVisibility(['is_profile_public' => true, 'show_photo' => true]));
        $message = new \App\Models\ChatMessage;
        $message->forceFill(['id' => \App\Support\BinaryUuid::generate(), 'user_id' => $user->getKey(), 'created_at' => now(), 'message' => 'Demo']);
        $message->setRelation('sender', $user);
        $service = new \App\Services\ChatService(new \App\Policies\ChatRoomPolicy);
        $project = new \ReflectionMethod($service, 'project');
        $result = $project->invoke($service, $message, $user, false);
        $this->assertSame(route('portfolio.photo', 'erik-chat'), $result['avatar_url']);
        $this->assertSame(route('portfolio.show', 'erik-chat'), $result['profile_url']);
        $user->profileVisibility->show_photo = false;
        $this->assertNull($project->invoke($service, $message, $user, false)['avatar_url']);
    }

    public function test_floating_launcher_visibility_does_not_leak_into_guest_auth_or_full_chat_pages(): void
    {
        Auth::forgetGuards();
        $this->assertStringNotContainsString('fc-launcher', Blade::render('<x-floating-chat />'));
        $this->actor('youth');
        $this->assertStringNotContainsString('fc-launcher', Blade::render('<x-floating-chat />'));
        foreach (['youth', 'admin', 'verifier'] as $role) {
            $this->route('/');
            $user = $this->actor($role);
            $user->forceFill(['email_verified_at' => now(), 'internal_active' => true]);
            $this->assertStringContainsString('Buka Chat SIPORA', Blade::render('<x-floating-chat />'));
            $this->route('/chat');
            $this->assertStringNotContainsString('fc-launcher', Blade::render('<x-floating-chat />'));
        }
        $this->assertStringNotContainsString('<x-floating-chat', file_get_contents(resource_path('views/layouts/auth.blade.php')));
    }

    public function test_room_directory_constrains_active_memberships_for_all_roles_without_loading_messages(): void
    {
        foreach (['youth', 'admin', 'verifier'] as $role) {
            $user = $this->actor($role);
            $user->id = \App\Support\BinaryUuid::generate();
            $query = app(\App\Services\ChatRoomDirectory::class)->communities($user);
            $sql = $query->toSql();
            $this->assertStringContainsString('exists', $sql);
            $this->assertStringContainsString('`membership_status` = ?', $sql);
            $this->assertStringContainsString('`organization_memberships`.`deleted_at` is null', $sql);
            $this->assertStringContainsString('`organizations`.`deleted_at` is null', $sql);
            $this->assertSame([$user->getKey(), 'active'], $query->getBindings());
            $this->assertStringNotContainsString('chat_messages', $sql);
        }
    }
}
