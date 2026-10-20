<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Security\RecaptchaService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\ResetPassword as ResetNotification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Symfony\Component\Mailer\Exception\TransportException;
use Tests\TestCase;

class AuthenticationReliabilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        Notification::fake();
        config(['features.recaptcha' => true, 'recaptcha.enabled' => true]);
        $this->mock(RecaptchaService::class)->shouldReceive('verify')->andReturnNull()->byDefault();
    }

    private function resetData(User $user, string $token): array
    {
        return ['email' => $user->email, 'token' => $token, 'password' => 'new-test-password', 'password_confirmation' => 'new-test-password'];
    }

    public function test_guest_verification_link_is_resumed_after_login_and_event_is_not_duplicated(): void
    {
        Event::fake([Verified::class]);
        $user = User::factory()->unverified()->create(['password' => 'old-test-password'])->assignRole('youth');
        $url = (new VerifyEmail)->toMail($user)->actionUrl;
        $this->get($url)->assertRedirect(route('login'));
        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'old-test-password', 'recaptcha_token' => 'test'])
            ->assertRedirect($url);
        $this->get($url)->assertRedirect(route('youth.home'));
        $this->get($url)->assertRedirect(route('youth.home'));
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        Event::assertDispatchedTimes(Verified::class, 1);
        $this->get(route('verification.notice'))->assertRedirect(route('youth.home'));
        $this->get(route('youth.home'))->assertOk();
    }

    public function test_verification_notice_shows_own_email_logout_and_resend_and_resend_is_throttled(): void
    {
        $user = User::factory()->unverified()->create()->assignRole('youth');
        $this->actingAs($user)->get(route('verification.notice'))->assertOk()->assertSee($user->email)->assertSee('Keluar / gunakan akun lain');
        for ($i = 0; $i < 6; $i++) {
            $this->post(route('verification.send'))->assertRedirect()->assertSessionHas('status');
        }
        $this->post(route('verification.send'))->assertTooManyRequests();
        Notification::assertSentToTimes($user, VerifyEmail::class, 6);
    }

    public function test_verified_user_does_not_send_more_verification_mail_and_guest_cannot_resend(): void
    {
        $user = User::factory()->create()->assignRole('youth');
        $this->actingAs($user)->post(route('verification.send'))->assertRedirect(route('youth.home'));
        Notification::assertNothingSent();
        Auth::logout();
        $this->post(route('verification.send'))->assertRedirect(route('login'));
    }

    public function test_broker_throttle_and_unknown_email_feedback_are_handled_without_false_new_delivery(): void
    {
        $this->get(route('password.request'))->assertOk();
        $user = User::factory()->create();
        $data = ['email' => $user->email, 'recaptcha_token' => 'test'];
        $this->post(route('password.email'), $data)->assertSessionHas('status');
        $this->post(route('password.email'), $data)->assertSessionHasErrors('email');
        Notification::assertSentToTimes($user, ResetNotification::class, 1);
        $this->post(route('password.email'), ['email' => 'unknown@example.test', 'recaptcha_token' => 'test'])
            ->assertSessionHas('status')->assertSessionHasNoErrors();
    }

    public function test_reset_changes_credentials_rotates_remember_token_invalidates_sessions_and_token(): void
    {
        Event::fake([PasswordReset::class]);
        $user = User::factory()->create(['email' => 'qa+reset@example.test', 'password' => 'old-test-password', 'remember_token' => 'old-remember'])->assignRole('youth');
        DB::table('sessions')->insert(['id' => 'old-auth-session', 'user_id' => $user->getKey(), 'payload' => '', 'last_activity' => time()]);
        $token = Password::createToken($user);
        $url = (new ResetNotification($token))->toMail($user)->actionUrl;
        $this->get($url)->assertOk()->assertSee($user->email)->assertSee('name="token"', false);
        $this->post(route('password.update'), $this->resetData($user, $token))->assertRedirect(route('login'));
        $this->assertTrue(Hash::check('new-test-password', $user->fresh()->password));
        $this->assertFalse(Hash::check('old-test-password', $user->fresh()->password));
        $this->assertNotSame('old-remember', $user->fresh()->remember_token);
        $this->assertDatabaseMissing('sessions', ['id' => 'old-auth-session']);
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);
        $this->post(route('password.update'), $this->resetData($user, $token))->assertSessionHasErrors('email');
        Event::assertDispatchedTimes(PasswordReset::class, 1);
        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'old-test-password', 'recaptcha_token' => 'test'])->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'new-test-password', 'recaptcha_token' => 'test'])->assertRedirect(route('youth.home'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_expired_mismatched_and_unconfirmed_tokens_cannot_change_password(): void
    {
        $owner = User::factory()->create(['password' => 'old-test-password']);
        $other = User::factory()->create(['password' => 'other-test-password']);
        $token = Password::createToken($owner);
        $this->assertNotSame($token, DB::table('password_reset_tokens')->where('email', $owner->email)->value('token'));
        $this->post(route('password.update'), $this->resetData($other, $token))->assertSessionHasErrors('email');
        $invalid = $this->resetData($owner, $token);
        $invalid['password_confirmation'] = 'different-password';
        $this->post(route('password.update'), $invalid)->assertSessionHasErrors('password')->assertSessionMissing('_old_input.token');
        DB::table('password_reset_tokens')->where('email', $owner->email)->update(['created_at' => now()->subMinutes(61)]);
        $this->post(route('password.update'), $this->resetData($owner, $token))->assertSessionHasErrors('email');
        $this->assertTrue(Hash::check('old-test-password', $owner->fresh()->password));
        $this->assertTrue(Hash::check('other-test-password', $other->fresh()->password));
    }

    public function test_mail_transport_failures_have_safe_feedback_and_registration_keeps_recoverable_account(): void
    {
        $user = User::factory()->unverified()->create()->assignRole('youth');
        Notification::shouldReceive('send')->andThrow(new TransportException('Transport failed: sensitive provider context'));
        $this->actingAs($user)->post(route('verification.send'))->assertSessionHasErrors('email');
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
        Auth::logout();
        $this->post(route('password.email'), ['email' => $user->email, 'recaptcha_token' => 'test'])->assertSessionHasErrors('email');
        $this->post(route('register.store'), $this->registrationSteps([...$this->registrationPayload(), 'name' => 'QA account', 'email' => 'qa-registration@example.test', 'password' => 'test-only-password', 'password_confirmation' => 'test-only-password', 'recaptcha_token' => 'test']))
            ->assertRedirect(route('verification.notice'))->assertSessionHas('error');
        $created = User::where('email', 'qa-registration@example.test')->firstOrFail();
        $this->assertAuthenticatedAs($created);
        $this->assertFalse($created->hasVerifiedEmail());
        $this->assertSame(['youth'], $created->getRoleNames()->all());
        $this->get(route('verification.notice'))->assertDontSee('sensitive provider context');
    }

    public function test_complete_manual_registration_and_reset_journeys_use_actual_mail_transport(): void
    {
        Notification::swap(new ChannelManager($this->app));
        config(['mail.default' => 'array']);
        $this->app['mail.manager']->forgetMailers();
        $transport = $this->app['mail.manager']->mailer()->getSymfonyTransport();
        $extractLink = function (string $path) use ($transport): string {
            $html = $transport->messages()->last()->getOriginalMessage()->getHtmlBody();
            preg_match('~href="([^"\\s]*'.$path.'[^"\\s]*)"~', $html, $matches);

            return html_entity_decode($matches[1], ENT_QUOTES);
        };

        $this->post(route('register.store'), $this->registrationSteps([...$this->registrationPayload(), 'name' => 'QA Journey', 'email' => 'journey@example.test', 'password' => 'old-test-password', 'password_confirmation' => 'old-test-password', 'recaptcha_token' => 'test']))
            ->assertRedirect(route('verification.notice'));
        $user = User::where('email', 'journey@example.test')->firstOrFail();
        $this->assertFalse($user->hasVerifiedEmail());
        $this->assertCount(1, $transport->messages());
        $this->get($extractLink('verify-email'))->assertRedirect(route('youth.home'));
        $this->get(route('youth.home'))->assertOk();
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $this->post(route('logout'))->assertRedirect(route('home'));
        $this->post(route('password.email'), ['email' => $user->email, 'recaptcha_token' => 'test'])->assertSessionHas('status');
        $this->assertCount(2, $transport->messages());
        $resetLink = $extractLink('reset-password');
        $this->get($resetLink)->assertOk();
        $token = basename(parse_url($resetLink, PHP_URL_PATH));
        $this->post(route('password.update'), $this->resetData($user, $token))->assertRedirect(route('login'));
        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'new-test-password', 'recaptcha_token' => 'test'])->assertRedirect(route('youth.home'));
        $this->get(route('youth.home'))->assertOk();
        $this->assertAuthenticatedAs($user);
        $this->assertFalse($user->fresh()->identity?->verification_status === 'verified');
    }
}
