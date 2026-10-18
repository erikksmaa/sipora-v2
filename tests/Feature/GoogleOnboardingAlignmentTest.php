<?php

namespace Tests\Feature;

use App\Actions\Auth\AuthenticateGoogleUser;
use App\Actions\Auth\RegisterUser;
use App\Actions\Youth\UpdateDomicileAction;
use App\Actions\Youth\UpdateYouthProfileAction;
use App\Models\User;
use App\Models\UserIdentity;
use App\Services\Youth\GoogleOnboardingService;
use App\Services\Youth\IdentityAnchorService;
use App\Services\Youth\YouthOnboardingService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Two\User as GoogleUser;
use Tests\TestCase;

class GoogleOnboardingAlignmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    private function google(): User
    {
        $identity = (new GoogleUser)->setRaw(['email_verified' => true])->map([
            'id' => 'alignment-provider', 'email' => 'alignment@gmail.com', 'name' => 'Google Youth',
            'avatar' => 'https://invalid.example/avatar.png',
        ]);

        return app(AuthenticateGoogleUser::class)->execute($identity);
    }

    public function test_pending_user_reuses_account_and_shared_wizard_without_password_or_remote_avatar(): void
    {
        $this->registrationPayload();
        $user = $this->google();
        $this->assertSame($user->getKey(), $this->google()->getKey());
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('user_social_accounts', 1);
        $this->actingAs($user)->get(route('youth.home'))->assertRedirect(route('youth.registration.edit'));
        $html = $this->get('http://127.0.0.1:8000/youth/registration')->assertOk()
            ->assertSee('Akun Google Anda sudah terhubung.')
            ->assertSee('Google Youth')->assertSee('alignment@gmail.com')
            ->assertSee('youthRegistration')->assertSee('readonly', false)
            ->assertDontSee('name="password"', false)->assertDontSee('password_confirmation')
            ->assertDontSee('alignment-provider')->assertDontSee('invalid.example')->getContent();
        $this->assertStringContainsString('data-registration-step="3"', $html);
        file_put_contents(storage_path('app/google-onboarding-rendered.html'), $html);
        $this->get(route('youth.biodata.edit'))->assertRedirect(route('youth.registration.edit'));
        $this->get(route('home'))->assertOk();
    }

    public function test_completion_is_atomic_uses_authenticated_email_and_matches_manual_domain(): void
    {
        $google = $this->google();
        $manualData = $this->registrationPayload(['email' => 'manual@example.test']);
        $manual = app(RegisterUser::class)->execute($manualData);
        $googleData = $this->registrationPayload(['email' => 'attacker@example.test', 'name' => 'Edited Google Name']);
        unset($googleData['password'], $googleData['password_confirmation']);
        $this->actingAs($google);
        $this->registrationSteps($googleData);
        $this->put(route('youth.registration.update'), $googleData + ['role' => 'admin'])
            ->assertSessionHasNoErrors()->assertRedirect(route('youth.identity-verification'));
        $google = $google->fresh();
        $this->assertSame('alignment@gmail.com', $google->email);
        $this->assertNull($google->password);
        $this->assertSame('Edited Google Name', $google->name);
        $this->assertSame(['youth'], $google->getRoleNames()->all());
        $this->assertFalse(app(GoogleOnboardingService::class)->pending($google));
        foreach ([$manual, $google] as $user) {
            $this->assertNotNull($user->profile()->first());
            $this->assertNotNull($user->primaryDomicile()->first()->village_id);
            $this->assertNotNull($user->identity()->first()->nationalId());
            $this->assertSame('unverified', $user->identity()->first()->verification_status);
            $this->assertSame(1, $user->interests()->count());
            $this->assertFalse($user->profileVisibility()->first()->is_profile_public);
        }
        $this->assertSame($manual->profile->birth_date->toDateString(), $google->profile->birth_date->toDateString());
        $this->assertSame($manual->profile->gender, $google->profile->gender);
        $this->assertSame(app(YouthOnboardingService::class)->state($manual)['biodataComplete'], app(YouthOnboardingService::class)->state($google)['biodataComplete']);
        $this->put(route('youth.registration.update'), $googleData)->assertSessionHasNoErrors();
        $this->assertDatabaseCount('user_profiles', 2);
        $this->assertDatabaseCount('user_addresses', 2);
        $this->assertDatabaseCount('user_interests', 2);
        $this->get(route('youth.registration.edit'))->assertRedirect(route('youth.home'));
        $this->get(route('youth.identity-verification'))->assertOk();
    }

    public function test_required_duplicate_age_gender_address_and_interest_validation_preserves_pending_state(): void
    {
        $owner = app(RegisterUser::class)->execute($this->registrationPayload());
        $user = $this->google();
        $data = $this->registrationPayload();
        $this->actingAs($user)->put(route('youth.registration.update'), [])->assertSessionHasErrors(['national_id', 'birth_date', 'gender', 'village_id', 'interests']);
        $this->put(route('youth.registration.update'), array_replace($data, [
            'national_id' => $owner->identity()->first()->nationalId(), 'gender' => 'other',
            'birth_date' => now()->subYears(10)->toDateString(), 'interests' => array_fill(0, 6, $data['interests'][0]),
            'village_id' => $data['administrative_area_id'],
        ]))->assertSessionHasErrors(['national_id', 'gender', 'birth_date', 'interests', 'village_id']);
        $this->put(route('youth.registration.update'), array_replace($data, ['administrative_area_id' => ['invalid']]))->assertSessionHasErrors('administrative_area_id');
        $this->assertTrue(app(GoogleOnboardingService::class)->pending($user));
        $this->assertNull($user->profile()->first());
        $this->assertNull($user->identity()->first());
        $this->assertNull(session()->getOldInput('national_id'));
        $this->get(route('youth.registration.edit'))->assertOk()->assertDontSee($owner->identity()->first()->nationalId());
    }

    public function test_legacy_completed_account_is_not_forced_to_supply_new_fields_and_saved_partial_data_survives(): void
    {
        $user = $this->google();
        $data = $this->registrationPayload();
        app(UpdateYouthProfileAction::class)->execute($user, ['full_name' => 'Saved Name', 'birth_date' => $data['birth_date'], 'gender' => 'male']);
        $this->actingAs($user)->get(route('youth.registration.edit'))->assertOk()->assertSee('Saved Name');
        app(UpdateDomicileAction::class)->execute($user, ['administrative_area_id' => $data['administrative_area_id']]);
        $this->assertFalse(app(GoogleOnboardingService::class)->pending($user));
        $this->assertNull($user->identity()->first()->nationalId());
        $this->get(route('youth.registration.edit'))->assertRedirect(route('youth.home'));
        $this->get(route('youth.home'))->assertOk();
    }

    public function test_final_transaction_rolls_back_all_domain_writes_on_failure(): void
    {
        $user = $this->google();
        $data = $this->registrationPayload();
        $this->app->instance(IdentityAnchorService::class, new class
        {
            public function assign(User $user, string $nik): UserIdentity
            {
                throw new \RuntimeException('Simulated persistence failure');
            }
        });
        $this->actingAs($user);
        $this->registrationSteps($data);
        $this->put(route('youth.registration.update'), $data)->assertStatus(500);
        $this->assertNull($user->profile()->first());
        $this->assertNull($user->primaryDomicile()->first());
        $this->assertNull($user->identity()->first());
        $this->assertSame(0, $user->interests()->count());
        $this->assertSame(1, $user->socialAccounts()->count());
    }

    public function test_non_google_user_cannot_submit_oauth_completion(): void
    {
        $user = User::factory()->create()->assignRole('youth');
        $this->actingAs($user)->put(route('youth.registration.update'), [])->assertForbidden();
    }
}
