<?php

namespace Tests\Feature;

use App\Actions\Auth\AuthenticateGoogleUser;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Two\User as GoogleUser;
use Tests\TestCase;

class RegistrationLinearWizardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['recaptcha.enabled' => false]);
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_manual_server_requires_validated_steps_in_order_before_creating_any_account(): void
    {
        $data = $this->registrationPayload();
        $this->postJson('/register', $data)->assertUnprocessable()->assertJsonValidationErrors('wizard');
        foreach ([1, 2] as $step) {
            $this->postJson('/registration/steps/'.$step, $data)->assertUnprocessable()->assertJsonValidationErrors('wizard');
        }
        $this->postJson('/registration/steps/0', array_replace($data, ['birth_place' => '']))
            ->assertUnprocessable()->assertJsonValidationErrors('birth_place');
        $this->postJson('/registration/steps/0', $data)->assertOk()->assertJsonPath('next_step', 1);
        $this->postJson('/registration/steps/2', $data)->assertUnprocessable()->assertJsonValidationErrors('wizard');
        $this->postJson('/registration/steps/1', array_replace($data, ['village_id' => '']))
            ->assertUnprocessable()->assertJsonValidationErrors('village_id');
        $this->postJson('/registration/steps/1', $data)->assertOk()->assertJsonPath('next_step', 2);
        $this->postJson('/register', $data)->assertUnprocessable()->assertJsonValidationErrors('wizard');
        $this->postJson('/registration/steps/2', array_replace($data, ['interests' => []]))
            ->assertUnprocessable()->assertJsonValidationErrors('interests');
        $this->postJson('/registration/steps/2', $data)->assertOk()->assertJsonPath('next_step', 3);
        foreach (['users', 'user_profiles', 'user_identities', 'user_addresses', 'user_interests'] as $table) {
            $this->assertDatabaseCount($table, 0);
        }
        $proofs = json_encode(session('registration_wizard'));
        $this->assertStringNotContainsString($data['national_id'], $proofs);
        $this->assertStringNotContainsString($data['password'], $proofs);
        $this->postJson('/register', array_replace($data, ['birth_place' => 'Taman']))
            ->assertUnprocessable()->assertJsonValidationErrors('wizard');
        $this->post('/register', $data)->assertRedirect(route('verification.notice'));
        $this->assertSame('Pemalang', User::firstOrFail()->profile->birth_place);
    }

    public function test_going_back_revalidates_changed_values_and_invalidates_later_approvals(): void
    {
        $data = $this->registrationPayload();
        $this->registrationSteps($data);
        $data['birth_place'] = 'Pekalongan';
        $this->postJson('/registration/steps/1', $data)->assertJsonValidationErrors('wizard');
        $this->postJson('/registration/steps/0', $data)->assertOk();
        $this->postJson('/register', $data)->assertJsonValidationErrors('wizard');
        $this->postJson('/registration/steps/2', $data)->assertJsonValidationErrors('wizard');
        $this->postJson('/registration/steps/1', $data)->assertOk();
        $this->postJson('/registration/steps/2', $data)->assertOk();
        $this->post('/register', $data)->assertRedirect(route('verification.notice'));
        $this->assertSame('Pekalongan', User::firstOrFail()->profile->birth_place);
    }

    public function test_google_uses_the_same_sequence_and_persists_required_birthplace_without_password(): void
    {
        $identity = (new GoogleUser)->setRaw(['email_verified' => true])->map([
            'id' => 'linear-google', 'email' => 'linear@gmail.com', 'name' => 'Google Youth',
        ]);
        $user = app(AuthenticateGoogleUser::class)->execute($identity);
        $data = $this->registrationPayload(['email' => 'ignored@example.test']);
        unset($data['password'], $data['password_confirmation']);
        $this->actingAs($user)->putJson(route('youth.registration.update'), $data)->assertJsonValidationErrors('wizard');
        $this->postJson('/registration/steps/2', $data)->assertJsonValidationErrors('wizard');
        $this->postJson('/registration/steps/0', array_replace($data, ['birth_place' => '']))->assertJsonValidationErrors('birth_place');
        $this->registrationSteps($data);
        $this->put(route('youth.registration.update'), $data)->assertRedirect(route('youth.identity-verification'));
        $user = $user->fresh();
        $this->assertSame('Pemalang', $user->profile->birth_place);
        $this->assertSame('linear@gmail.com', $user->email);
        $this->assertNull($user->password);
        $this->assertSame(['youth'], $user->getRoleNames()->all());
        $this->assertSame('unverified', $user->identity->verification_status);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_refresh_query_and_forged_step_cannot_bypass_required_data(): void
    {
        $data = $this->registrationPayload();
        $this->get('/register?step=3')->assertOk()->assertDontSee('goTo(index)', false)
            ->assertDontSee('Buka langkah')->assertSee('Tempat lahir')->assertSee('Contoh: Pemalang');
        $this->postJson('/registration/steps/3', $data)->assertNotFound();
        $this->postJson('/register', $data)->assertJsonValidationErrors('wizard');
        $this->registrationSteps($data);
        $this->get('/register?step=3')->assertOk();
        $this->postJson('/register', [])->assertJsonValidationErrors(['name', 'national_id', 'birth_place', 'village_id', 'interests']);
        $this->assertDatabaseCount('users', 0);
    }
}
