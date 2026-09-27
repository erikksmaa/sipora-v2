<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Security\RecaptchaService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
        Notification::fake();
        config(['recaptcha.enabled' => true]);
        $this->seed(RolePermissionSeeder::class);
        $this->mock(RecaptchaService::class)->shouldReceive('verify')->andReturnNull()->byDefault();
    }

    private function registration(): array
    {
        return ['name' => 'Youth Example', 'email' => 'YOUTH@example.test', 'password' => 'test-only-password',
            'password_confirmation' => 'test-only-password', 'recaptcha_token' => 'test-token', 'role' => 'admin'];
    }

    public function test_registration_normalizes_email_assigns_only_youth_and_sends_verification(): void
    {
        $this->post('/register', $this->registration())->assertRedirect('/verify-email');
        $user = User::firstOrFail();
        $this->assertSame('youth@example.test', $user->email);
        $this->assertSame(['youth'], $user->getRoleNames()->all());
        $this->assertFalse($user->hasVerifiedEmail());
        $this->assertTrue(Hash::check('test-only-password', $user->password));
        Notification::assertSentTo($user, VerifyEmail::class);
        $this->assertAuthenticatedAs($user);
        $this->get('/youth/home')->assertRedirect('/verify-email');
    }

    public function test_registration_requires_valid_fields_and_captcha(): void
    {
        $this->post('/register', ['email' => 'invalid'])->assertSessionHasErrors(['name', 'email', 'password', 'recaptcha_token']);
        $this->assertDatabaseCount('users', 0);
        $this->mock(RecaptchaService::class)->shouldReceive('verify')->andThrow(ValidationException::withMessages(['recaptcha_token' => 'Rejected']));
        $this->post('/register', $this->registration())->assertSessionHasErrors('recaptcha_token');
        $this->assertDatabaseCount('users', 0);
    }

    public function test_local_auth_form_can_submit_without_token_when_recaptcha_is_explicitly_disabled(): void
    {
        config(['recaptcha.enabled' => false]);
        $data = $this->registration();
        unset($data['recaptcha_token']);

        $this->post('/register', $data)->assertRedirect('/verify-email');
        $this->assertDatabaseHas('users', ['email' => 'youth@example.test']);
    }

    public function test_login_logout_and_database_session_round_trip(): void
    {
        config(['session.driver' => 'binary-database']);
        $this->app['session']->forgetDrivers();
        $user = User::factory()->create(['password' => 'test-only-password'])->assignRole('youth');
        $this->post('/login', ['email' => $user->email, 'password' => 'test-only-password', 'remember' => '1', 'recaptcha_token' => 'test'])
            ->assertRedirect('/youth/home');
        $this->assertAuthenticatedAs($user);
        $this->assertSame($user->getKey(), DB::table('sessions')->where('user_id', $user->getKey())->value('user_id'));
        $sessionId = $this->app['session']->driver()->getId();
        Auth::forgetGuards();
        $this->withCookie(config('session.cookie'), $sessionId)->get('/youth/home')->assertOk();
        $this->assertAuthenticatedAs($user);
        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_remember_me_provider_handles_text_uuid_and_rejects_bad_token(): void
    {
        $user = User::factory()->create(['remember_token' => 'test-remember-token']);
        $provider = Auth::guard()->getProvider();
        $this->assertSame($user->uuid(), $provider->retrieveByToken($user->uuid(), 'test-remember-token')->uuid());
        $this->assertNull($provider->retrieveByToken($user->uuid(), 'bad-token'));
        $this->assertNull($provider->retrieveById('invalid'));
    }

    public function test_wrong_password_and_oauth_only_password_fail_safely(): void
    {
        $user = User::factory()->create(['password' => null]);
        $this->post('/login', ['email' => $user->email, 'password' => 'incorrect', 'recaptcha_token' => 'test'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_public_auth_is_rate_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => 'missing@example.test', 'password' => 'incorrect', 'recaptcha_token' => 'test']);
        }
        $this->post('/login', ['email' => 'missing@example.test', 'password' => 'incorrect', 'recaptcha_token' => 'test'])->assertTooManyRequests();
    }

    public function test_signed_email_verification_works_with_binary_id(): void
    {
        $user = User::factory()->unverified()->create()->assignRole('youth');
        $url = (new VerifyEmail)->toMail($user)->actionUrl;
        $this->assertStringContainsString($user->uuid(), $url);
        $this->actingAs($user)->get($url)->assertRedirect('/youth/home');
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $this->get('/youth/home')->assertOk();
    }

    public function test_email_verification_rejects_wrong_user_bad_signature_and_expired_link(): void
    {
        $owner = User::factory()->unverified()->create()->assignRole('youth');
        $other = User::factory()->unverified()->create()->assignRole('youth');
        $url = (new VerifyEmail)->toMail($owner)->actionUrl;
        $this->actingAs($other)->get($url)->assertForbidden();
        $this->actingAs($owner)->get($url.'tampered')->assertForbidden();
        $expired = URL::temporarySignedRoute('verification.verify', now()->subMinute(), ['id' => $owner->uuid(), 'hash' => sha1($owner->email)]);
        $this->get($expired)->assertForbidden();
        $this->assertFalse($owner->fresh()->hasVerifiedEmail());
    }

    public function test_verification_can_be_resent(): void
    {
        $user = User::factory()->unverified()->create();
        $this->actingAs($user)->post('/email/verification-notification')->assertSessionHas('status');
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_password_reset_sends_notification_and_changes_password(): void
    {
        $user = User::factory()->create(['password' => null])->assignRole('youth');
        $this->post('/forgot-password', ['email' => $user->email, 'recaptcha_token' => 'test'])->assertSessionHas('status');
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user): bool {
            $this->post('/reset-password', ['token' => $notification->token, 'email' => $user->email,
                'password' => 'new-test-password', 'password_confirmation' => 'new-test-password'])
                ->assertRedirect('/login');

            return true;
        });
        $this->assertTrue(Hash::check('new-test-password', $user->fresh()->password));
    }

    public function test_invalid_reset_token_does_not_change_password(): void
    {
        $user = User::factory()->create();
        $original = $user->password;
        $this->post('/reset-password', ['token' => 'invalid', 'email' => $user->email,
            'password' => 'new-test-password', 'password_confirmation' => 'new-test-password'])->assertSessionHasErrors('email');
        $this->assertSame($original, $user->fresh()->password);
    }

    public function test_csrf_protection_is_active_outside_test_bypass(): void
    {
        $this->app->instance('env', 'local');
        $this->post('/login', ['email' => 'youth@example.test'])->assertStatus(419);
    }
}
