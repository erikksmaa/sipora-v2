<?php

namespace Tests\Feature;

use App\Models\AuditEntry;
use App\Models\User;
use App\Services\InternalAccountService;
use App\Services\Security\RecaptchaService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class InternalAccountManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        Notification::fake();
        $this->mock(RecaptchaService::class)->shouldReceive('verify')->andReturnNull()->byDefault();
    }

    private function account(string $role = 'admin'): User
    {
        return User::factory()->create(['password' => 'old-password'])->assignRole($role);
    }

    public function test_password_pages_and_navigation_keep_their_workspace(): void
    {
        foreach (['admin', 'verifier'] as $role) {
            $user = $this->account($role);
            $this->actingAs($user)->get(route($role.'.account.password.edit'))->assertOk()
                ->assertSee('Kelola Password')->assertSee('data-workspace="'.$role.'"', false)
                ->assertSee('name="current_password"', false);
        }
        $this->get(route('admin.internal-users.index'))->assertForbidden();
        $this->actingAs($this->account('youth'))->get(route('verifier.account.password.edit'))->assertForbidden();
        $this->get(route('internal.password.edit'))->assertForbidden();
    }

    public function test_directory_excludes_youth_and_filters_and_paginates(): void
    {
        $admin = $this->account();
        $youth = $this->account('youth');
        User::factory()->count(16)->create()->each(fn ($user) => $user->assignRole('verifier'));
        $this->actingAs($admin)->get(route('admin.internal-users.index'))->assertOk()
            ->assertDontSee($youth->email)->assertViewHas('users', fn ($users) => $users->total() === 17 && $users->count() === 15);
        $this->get(route('admin.internal-users.index', ['role' => 'admin', 'active' => '1', 'verified' => '1']))
            ->assertOk()->assertViewHas('users', fn ($users) => $users->total() === 1);
        $this->get(route('admin.internal-users.show', $youth))->assertForbidden();
        $this->get(route('admin.internal-users.create'))->assertOk();
        $this->get(route('admin.internal-users.edit', User::role('verifier')->first()))->assertOk();
        $this->get(route('admin.internal-users.activity-log'))->assertOk();
    }

    public function test_create_verifier_returns_random_password_once_without_plaintext_storage(): void
    {
        $admin = $this->account();
        $response = $this->actingAs($admin)->post(route('admin.internal-users.store'), [
            'name' => 'Verifier Baru', 'email' => 'NEW@example.test', 'role' => 'verifier',
        ])->assertOk()->assertViewIs('admin.internal-users.temporary');
        $secret = $response->viewData('temporaryPassword');
        $user = User::where('email', 'new@example.test')->firstOrFail();
        $this->assertTrue(Hash::check($secret, $user->password));
        $this->assertGreaterThanOrEqual(16, strlen($secret));
        $this->assertMatchesRegularExpression('/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9])/s', $secret);
        $this->assertTrue($user->must_change_password);
        $this->assertNull($user->email_verified_at);
        $this->assertSame(['verifier'], $user->getRoleNames()->all());
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        $this->assertStringNotContainsString($secret, AuditEntry::all()->toJson());
        $this->assertStringNotContainsString($secret, json_encode(session()->all()));
        $this->get(route('admin.internal-users.show', $user))->assertOk()->assertDontSee($secret);
        $this->assertSame(2, AuditEntry::where('log_name', 'internal_accounts')->count());
    }

    public function test_admin_creation_needs_actor_password_and_rejects_role_injection(): void
    {
        $this->actingAs($this->account());
        $data = ['name' => 'Admin Baru', 'email' => 'admin-new@example.test', 'role' => 'admin'];
        $this->post(route('admin.internal-users.store'), $data)->assertSessionHasErrors('admin_password');
        $this->post(route('admin.internal-users.store'), $data + ['admin_password' => 'wrong-password'])->assertSessionHasErrors('admin_password')->assertSessionMissing('_old_input.admin_password');
        $this->post(route('admin.internal-users.store'), $data + ['admin_password' => 'old-password'])->assertOk();
        $this->assertTrue(User::where('email', $data['email'])->firstOrFail()->hasRole('admin'));
        $this->post(route('admin.internal-users.store'), ['name' => 'Bad', 'email' => 'bad@example.test', 'role' => 'youth'])->assertSessionHasErrors('role');
        $this->post(route('admin.internal-users.store'), ['name' => 'Bad', 'email' => 'bad@example.test', 'role' => 'verifier', 'roles' => ['admin'], 'internal_active' => true])->assertSessionHasErrors(['roles', 'internal_active']);
    }

    public function test_edit_reverifies_changed_email_and_does_not_allow_role_changes(): void
    {
        $admin = $this->account();
        $verifier = $this->account('verifier');
        Password::createToken($verifier);
        $this->actingAs($admin)->patch(route('admin.internal-users.update', $verifier), ['name' => 'Updated', 'email' => $verifier->email])->assertRedirect();
        $this->assertNotNull($verifier->fresh()->email_verified_at);
        $this->patch(route('admin.internal-users.update', $verifier), ['name' => 'Updated', 'email' => 'changed@example.test'])->assertRedirect();
        $this->assertNull($verifier->fresh()->email_verified_at);
        $this->assertSame(1, $verifier->fresh()->internal_session_version);
        $this->assertDatabaseCount('password_reset_tokens', 0);
        $this->patch(route('admin.internal-users.update', $verifier), ['name' => 'Updated', 'email' => 'changed@example.test', 'role' => 'admin'])->assertSessionHasErrors('role');
        $this->assertTrue($verifier->fresh()->hasRole('verifier'));
        $this->patch(route('admin.internal-users.update', $admin), ['name' => 'Self', 'email' => $admin->email])->assertForbidden();
    }

    public function test_admin_targets_require_confirmation_and_self_actions_are_blocked(): void
    {
        $admin = $this->account();
        $other = $this->account();
        $this->actingAs($admin)->post(route('admin.internal-users.temporary', $other))->assertSessionHasErrors('admin_password');
        foreach (['temporary', 'active', 'reset-link', 'verification'] as $action) {
            $this->post(route('admin.internal-users.'.$action, $admin), ['active' => 0, 'admin_password' => 'old-password'])->assertForbidden();
        }
        $this->post(route('admin.internal-users.active', $other), ['active' => 0, 'admin_password' => 'old-password'])->assertRedirect();
        $this->assertFalse($other->fresh()->internal_active);
        // Even if a previously authorized request reaches the service after its actor was disabled,
        // the remaining active Administrator must stay protected.
        $this->expectException(ValidationException::class);
        app(InternalAccountService::class)->setActive($other, $admin, false, 'old-password');
    }

    public function test_nonactive_account_cannot_login_and_history_is_preserved(): void
    {
        $admin = $this->account();
        $verifier = $this->account('verifier');
        $this->actingAs($admin)->post(route('admin.internal-users.active', $verifier), ['active' => 0])->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => $verifier->email]);
        $this->assertSame(1, AuditEntry::where('event', 'internal_account_deactivated')->count());
        Auth::logout();
        $this->post(route('login.store'), ['email' => $verifier->email, 'password' => 'old-password', 'recaptcha_token' => 'test'])->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->actingAs($admin)->post(route('admin.internal-users.active', $verifier), ['active' => 1])->assertRedirect();
        $this->assertTrue($verifier->fresh()->internal_active);
    }

    public function test_temporary_recovery_forces_change_and_revokes_old_password(): void
    {
        $admin = $this->account();
        $verifier = $this->account('verifier');
        $response = $this->actingAs($admin)->post(route('admin.internal-users.temporary', $verifier))->assertOk();
        $temporary = $response->viewData('temporaryPassword');
        $this->assertFalse(Hash::check('old-password', $verifier->fresh()->password));
        Auth::logout();
        $this->post(route('login.store'), ['email' => $verifier->email, 'password' => $temporary, 'recaptcha_token' => 'test'])->assertRedirect(route('internal.password.edit'));
        $this->get(route('verifier.dashboard'))->assertRedirect(route('internal.password.edit'));
        $this->get(route('verification.notice'))->assertRedirect(route('internal.password.edit'));
        $this->get(route('internal.password.edit'))->assertOk()->assertDontSee('name="current_password"', false);
        $this->put(route('internal.password.update'), ['password' => $temporary, 'password_confirmation' => $temporary])->assertSessionHasErrors('password');
        $this->put(route('internal.password.update'), ['password' => 'permanent-password', 'password_confirmation' => 'permanent-password'])->assertRedirect(route('verifier.dashboard'));
        $this->assertFalse($verifier->fresh()->must_change_password);
        $this->assertTrue(Hash::check('permanent-password', $verifier->fresh()->password));
        $this->assertFalse(Hash::check($temporary, $verifier->fresh()->password));
        $this->get(route('verifier.dashboard'))->assertOk();
        $this->put(route('internal.password.update'), ['password' => 'bypass-password', 'password_confirmation' => 'bypass-password'])->assertForbidden();
    }

    public function test_own_password_requires_current_and_confirmation_and_keeps_current_session(): void
    {
        foreach (['admin', 'verifier'] as $role) {
            $user = $this->account($role);
            session()->forget('internal_session_version');
            $this->actingAs($user);
            $data = ['password' => 'new-password', 'password_confirmation' => 'new-password'];
            $this->put(route($role.'.account.password.update'), $data)->assertSessionHasErrors('current_password');
            $this->put(route($role.'.account.password.update'), $data + ['current_password' => 'incorrect'])->assertSessionHasErrors('current_password');
            $this->put(route($role.'.account.password.update'), $data + ['current_password' => 'old-password'])->assertRedirect(route($role.'.account.password.edit'));
            $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
            $this->get(route($role.'.account.password.edit'))->assertOk();
        }
    }

    public function test_session_versions_reject_stale_array_sessions_and_database_sessions_are_deleted(): void
    {
        $admin = $this->account();
        $verifier = $this->account('verifier');
        config(['session.driver' => 'binary-database']);
        DB::table('sessions')->insert(['id' => 'internal-old-session', 'user_id' => $verifier->getKey(), 'payload' => '', 'last_activity' => time()]);
        app(InternalAccountService::class)->temporaryReset($admin, $verifier);
        $this->assertDatabaseMissing('sessions', ['id' => 'internal-old-session']);
        config(['session.driver' => 'array']);
        $this->actingAs($verifier)->withSession(['internal_session_version' => 0])->get(route('verifier.dashboard'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_reset_link_uses_existing_broker_and_token_cannot_be_reused(): void
    {
        $admin = $this->account();
        $verifier = $this->account('verifier');
        $verifier->forceFill(['must_change_password' => true])->save();
        $this->actingAs($admin)->post(route('admin.internal-users.reset-link', $verifier))->assertRedirect();
        $token = null;
        Notification::assertSentTo($verifier, ResetPassword::class, function ($notification) use (&$token) {
            $token = $notification->token;

            return true;
        });
        $this->post(route('admin.internal-users.reset-link', $verifier))->assertSessionHasErrors('email');
        Auth::logout();
        $data = ['email' => $verifier->email, 'token' => $token, 'password' => 'broker-password', 'password_confirmation' => 'broker-password'];
        $this->post(route('password.update'), $data)->assertRedirect(route('login'));
        $this->assertTrue(Hash::check('broker-password', $verifier->fresh()->password));
        $this->assertFalse($verifier->fresh()->must_change_password);
        $this->post(route('password.update'), $data)->assertSessionHasErrors('email');
        $this->assertStringNotContainsString($token, AuditEntry::all()->toJson());
    }

    public function test_verification_resend_uses_signed_notification_and_cannot_mark_verified(): void
    {
        $admin = $this->account();
        $verifier = $this->account('verifier');
        $verifier->forceFill(['email_verified_at' => null])->save();
        $this->actingAs($admin)->post(route('admin.internal-users.verification', $verifier))->assertRedirect();
        Notification::assertSentTo($verifier, VerifyEmail::class, fn ($notification) => str_contains($notification->toMail($verifier)->actionUrl, 'signature='));
        $this->assertNull($verifier->fresh()->email_verified_at);
        $this->patch(route('admin.internal-users.update', $verifier), ['name' => $verifier->name, 'email' => $verifier->email, 'email_verified_at' => now()->toDateTimeString()])->assertSessionHasErrors('email_verified_at');
        $this->assertNull($verifier->fresh()->email_verified_at);
    }

    public function test_password_validation_rejects_unconfirmed_short_long_and_unchanged_values(): void
    {
        $user = $this->account('verifier');
        $this->actingAs($user);
        foreach ([['short', 'short'], [str_repeat('x', 73), str_repeat('x', 73)], ['new-password', 'different-password'], ['old-password', 'old-password']] as [$password, $confirmation]) {
            $this->put(route('verifier.account.password.update'), ['current_password' => 'old-password', 'password' => $password, 'password_confirmation' => $confirmation])->assertSessionHasErrors('password');
        }
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
    }

    public function test_recovery_is_rate_limited_and_expired_tokens_cannot_change_internal_credentials(): void
    {
        $admin = $this->account();
        $verifier = $this->account('verifier');
        $this->actingAs($admin);
        for ($i = 0; $i < 6; $i++) {
            $this->post(route('admin.internal-users.reset-link', $verifier))->assertRedirect();
        }
        $this->post(route('admin.internal-users.reset-link', $verifier))->assertTooManyRequests();
        $token = Password::createToken($verifier);
        DB::table('password_reset_tokens')->where('email', $verifier->email)->update(['created_at' => now()->subHours(3)]);
        Auth::logout();
        $this->post(route('password.update'), ['email' => $verifier->email, 'token' => $token, 'password' => 'new-password', 'password_confirmation' => 'new-password'])->assertSessionHasErrors('email');
        $this->assertTrue(Hash::check('old-password', $verifier->fresh()->password));
    }

    public function test_disabled_logged_in_account_is_denied_and_youth_stays_out_of_directory(): void
    {
        $admin = $this->account();
        $verifier = $this->account('verifier');
        $verifier->forceFill(['internal_active' => false])->save();
        $this->actingAs($verifier)->get(route('verifier.dashboard'))->assertRedirect(route('login'));
        $this->assertGuest();
        $mixed = $this->account('youth')->assignRole('verifier');
        $this->actingAs($admin)->get(route('admin.internal-users.index'))->assertOk()->assertDontSee($mixed->email);
        $this->get(route('admin.internal-users.show', $mixed))->assertForbidden();
    }
}
