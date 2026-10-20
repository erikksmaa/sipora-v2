<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Security\RecaptchaService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Laravel\Socialite\Facades\Socialite;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AuthFeatureFlagsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        Notification::fake();
        Http::fake();
        Http::preventStrayRequests();
        config([
            'features.google_oauth' => false,
            'features.recaptcha' => false,
            'recaptcha.enabled' => true,
            'recaptcha.site_key' => 'test-site-marker',
            'recaptcha.secret_key' => 'test-secret-marker',
            'services.google.client_id' => 'test-client-marker',
            'services.google.client_secret' => 'test-client-secret-marker',
            'services.google.redirect' => 'http://localhost/auth/google/callback',
        ]);
    }

    private function productionWithCsrf(): array
    {
        // The test environment guard has already verified the isolated test DB.
        $this->app->instance('env', 'production');
        $this->withSession(['_token' => 'test-csrf-token']);

        return ['_token' => 'test-csrf-token'];
    }

    public function test_disabled_features_hide_provider_controls_and_tokens_even_with_old_keys_configured(): void
    {
        $this->productionWithCsrf();
        foreach (['login', 'register', 'password.request'] as $route) {
            $this->get(route($route))->assertOk()
                ->assertDontSee(route('google.redirect'), false)
                ->assertDontSee('name="recaptcha_token"', false)
                ->assertDontSee('recaptcha/api.js', false)
                ->assertDontSee('recaptchaForm(', false)
                ->assertDontSee('test-site-marker')
                ->assertDontSee('test-secret-marker')
                ->assertDontSee('test-client-marker')
                ->assertDontSee('test-client-secret-marker')
                ->assertSee('name="_token"', false);
        }
    }

    public function test_production_manual_registration_completes_existing_wizard_without_captcha(): void
    {
        $data = $this->registrationPayload();
        $csrf = $this->productionWithCsrf();
        $data = $csrf + $data;
        unset($data['recaptcha_token']);
        $this->mock(RecaptchaService::class)->shouldNotReceive('verify');
        $this->post(route('register.store'), $this->registrationSteps($data))
            ->assertRedirect(route('verification.notice'))->assertSessionHasNoErrors();
        $user = User::where('email', $data['email'])->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertSame(['youth'], $user->getRoleNames()->all());
        $this->assertTrue(Hash::check($data['password'], $user->password));
        $this->assertSame('Pemalang', $user->profile->birth_place);
        Notification::assertSentTo($user, VerifyEmail::class);
        Http::assertNothingSent();
    }

    public function test_production_manual_login_and_password_recovery_do_not_call_captcha(): void
    {
        $csrf = $this->productionWithCsrf();
        $user = User::factory()->create(['password' => 'test-only-password'])->assignRole('youth');
        $this->mock(RecaptchaService::class)->shouldNotReceive('verify');
        $this->post(route('password.email'), $csrf + ['email' => $user->email])
            ->assertSessionHasNoErrors()->assertSessionHas('status');
        Notification::assertSentTo($user, ResetPassword::class);
        $this->post(route('login.store'), $csrf + ['email' => $user->email, 'password' => 'test-only-password'])
            ->assertRedirect(route('youth.home'))->assertSessionHasNoErrors();
        $this->assertAuthenticatedAs($user);
        Http::assertNothingSent();
    }

    public function test_disabled_captcha_service_short_circuits_in_production_even_without_keys(): void
    {
        $this->productionWithCsrf();
        config(['recaptcha.secret_key' => null]);
        app(RecaptchaService::class)->verify('', 'login');
        Http::assertNothingSent();
    }

    public function test_disabled_google_redirect_and_callback_never_contact_socialite(): void
    {
        Socialite::shouldReceive('driver')->never();
        foreach (['google.redirect', 'google.callback'] as $route) {
            $this->get(route($route))->assertRedirect(route('login'))
                ->assertSessionHas('status', 'Login dengan Google sedang dinonaktifkan sementara.');
        }
        $this->get(route('login'))->assertOk()->assertSee('Login dengan Google sedang dinonaktifkan sementara.');
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
        Http::assertNothingSent();
    }

    public static function sensitiveRoutes(): array
    {
        return array_map(fn ($route) => [$route], ['login.store', 'register.store', 'password.email', 'password.update']);
    }

    #[DataProvider('sensitiveRoutes')]
    public function test_disabled_features_keep_auth_endpoint_rate_limits(string $route): void
    {
        $csrf = $this->productionWithCsrf();
        for ($i = 0; $i < 5; $i++) {
            $this->post(route($route), $csrf + ['email' => 'invalid'])->assertRedirect();
        }
        $this->post(route($route), $csrf + ['email' => 'invalid'])->assertTooManyRequests();
    }

    public function test_disabled_features_keep_csrf_and_field_validation(): void
    {
        $this->productionWithCsrf();
        $this->post(route('login.store'), ['email' => 'invalid'])->assertStatus(419);
        $this->post(route('login.store'), ['_token' => 'test-csrf-token', 'email' => 'invalid'])
            ->assertSessionHasErrors(['email', 'password'])->assertSessionDoesntHaveErrors('recaptcha_token');
    }

    public function test_disabling_captcha_does_not_bypass_registration_sequence(): void
    {
        $data = $this->registrationPayload();
        $csrf = $this->productionWithCsrf();
        $data = $csrf + $data;
        unset($data['recaptcha_token']);
        $this->post(route('register.store'), $data)->assertSessionHasErrors();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_disabled_features_keep_verification_resend_throttle(): void
    {
        $csrf = $this->productionWithCsrf();
        $user = User::factory()->unverified()->create()->assignRole('youth');
        $this->actingAs($user);
        for ($i = 0; $i < 6; $i++) {
            $this->post(route('verification.send'), $csrf)->assertRedirect();
        }
        $this->post(route('verification.send'), $csrf)->assertTooManyRequests();
    }

    public function test_reenabling_flags_restores_provider_ui_and_required_captcha(): void
    {
        config(['features.google_oauth' => true, 'features.recaptcha' => true]);
        foreach (['login', 'register'] as $route) {
            $this->get(route($route))->assertOk()->assertSee(route('google.redirect'), false)
                ->assertSee('name="recaptcha_token"', false)->assertSee('recaptcha/api.js', false)
                ->assertDontSee('test-secret-marker')->assertDontSee('test-client-secret-marker');
        }
        $this->post(route('login.store'), ['email' => 'youth@example.test', 'password' => 'test-only-password'])
            ->assertSessionHasErrors('recaptcha_token');
        Http::assertNothingSent();
    }
}
