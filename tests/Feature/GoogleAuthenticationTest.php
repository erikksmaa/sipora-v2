<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserSocialAccount;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\User as GoogleUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
        $this->seed(RolePermissionSeeder::class);
        config(['services.google.client_id' => 'test-client', 'services.google.client_secret' => 'test-secret',
            'services.google.redirect' => 'http://localhost/auth/google/callback']);
    }

    private function identity(string $email = 'youth@gmail.com', array $claims = [], string $id = 'google-test-id'): GoogleUser
    {
        return (new GoogleUser)->setRaw(array_merge(['email_verified' => true], $claims))->map([
            'id' => $id, 'email' => $email, 'name' => 'Google Youth', 'avatar' => null,
        ]);
    }

    private function mockIdentity(GoogleUser $identity): void
    {
        $provider = Mockery::mock(GoogleProvider::class);
        $provider->shouldReceive('user')->once()->andReturn($identity);
        Socialite::shouldReceive('driver')->with('google')->once()->andReturn($provider);
    }

    public function test_google_redirect_uses_stateful_socialite(): void
    {
        $response = $this->get('/auth/google');
        $response->assertRedirect();
        $location = $response->headers->get('Location');
        $this->assertStringStartsWith('https://accounts.google.com/', $location);
        parse_str(parse_url($location, PHP_URL_QUERY), $parameters);
        $this->assertNotEmpty($parameters['state']);
        $this->assertSame($parameters['state'], session('state'));
    }

    public function test_new_google_user_gets_only_youth_and_no_password_or_tokens(): void
    {
        $this->mockIdentity($this->identity());
        $this->get('/auth/google/callback')->assertRedirect('/youth/home');
        $user = User::firstOrFail();
        $this->assertSame(['youth'], $user->getRoleNames()->all());
        $this->assertTrue($user->hasVerifiedEmail());
        $this->assertNull($user->password);
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseCount('user_social_accounts', 1);
        $this->assertSame($user->getKey(), UserSocialAccount::first()->user_id);
        $this->assertArrayNotHasKey('access_token', UserSocialAccount::first()->getAttributes());
    }

    public function test_verified_matching_gmail_account_links_without_duplicate(): void
    {
        $user = User::factory()->create(['email' => 'youth@gmail.com'])->assignRole('youth');
        $this->mockIdentity($this->identity());
        $this->get('/auth/google/callback')->assertRedirect('/youth/home');
        $this->assertDatabaseCount('users', 1);
        $this->assertAuthenticatedAs($user);
    }

    public function test_verified_matching_workspace_account_links_without_duplicate(): void
    {
        $user = User::factory()->create(['email' => 'youth@example.test'])->assignRole('youth');
        $this->mockIdentity($this->identity('youth@example.test', ['hd' => 'example.test']));
        $this->get('/auth/google/callback')->assertRedirect('/youth/home');
        $this->assertDatabaseCount('users', 1);
        $this->assertAuthenticatedAs($user);
    }

    public function test_existing_provider_identity_is_used_even_if_email_changes(): void
    {
        $user = User::factory()->create(['email' => 'old@gmail.com'])->assignRole('youth');
        $user->socialAccounts()->create(['provider' => 'google', 'provider_user_id' => 'google-test-id', 'provider_email' => $user->email]);
        $this->mockIdentity($this->identity('new@gmail.com'));
        $this->get('/auth/google/callback')->assertRedirect('/youth/home');
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('user_social_accounts', 1);
        $this->assertAuthenticatedAs($user);
        $this->assertSame('old@gmail.com', $user->fresh()->email);
    }

    public function test_unverified_existing_account_is_not_taken_over_or_duplicated(): void
    {
        User::factory()->unverified()->create(['email' => 'youth@gmail.com'])->assignRole('youth');
        $this->mockIdentity($this->identity());
        $this->get('/auth/google/callback')->assertRedirect('/login')->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('user_social_accounts', 0);
    }

    public function test_government_account_is_not_automatically_linked(): void
    {
        User::factory()->create(['email' => 'youth@gmail.com'])->assignRole('admin');
        $this->mockIdentity($this->identity());
        $this->get('/auth/google/callback')->assertRedirect('/login')->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->assertDatabaseCount('user_social_accounts', 0);
    }

    public function test_unverified_google_email_fails_closed(): void
    {
        $this->mockIdentity($this->identity('youth@gmail.com', ['email_verified' => false]));
        $this->get('/auth/google/callback')->assertRedirect('/login')->assertSessionHasErrors('email');
        $this->assertDatabaseCount('users', 0);
    }

    public function test_non_authoritative_google_email_fails_closed(): void
    {
        $this->mockIdentity($this->identity('youth@example.test'));
        $this->get('/auth/google/callback')->assertRedirect('/login')->assertSessionHasErrors('email');
        $this->assertDatabaseCount('users', 0);
    }

    public function test_callback_state_failure_is_safe(): void
    {
        // Real Socialite rejects state before any external request is made.
        $this->get('/auth/google/callback?state=invalid&code=test')->assertRedirect('/login')->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_cancelled_and_unconfigured_google_authentication_fail_safely(): void
    {
        $this->get('/auth/google/callback?error=access_denied')->assertRedirect('/login');
        config(['services.google.client_secret' => null]);
        $this->get('/auth/google')->assertStatus(503);
        $this->get('/auth/google/callback')->assertRedirect('/login');
        $this->assertGuest();
    }
}
