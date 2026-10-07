<?php

namespace Tests\Feature;

use App\Actions\Youth\SyncUserInterestsAction;
use App\Actions\Youth\SyncUserSkillsAction;
use App\Models\AdministrativeArea;
use App\Models\Interest;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserIdentity;
use App\Services\Youth\YouthOnboardingService;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\YouthEnrichmentSeeder;
use Database\Seeders\YouthFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class YouthProgressiveOnboardingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([RolePermissionSeeder::class, YouthFoundationSeeder::class, YouthEnrichmentSeeder::class]);
    }

    private function youth(array $attributes = []): User
    {
        return User::factory()->create($attributes)->assignRole('youth');
    }

    private function biodata(User $user): void
    {
        $this->actingAs($user)->put(route('youth.biodata.update'), [
            'full_name' => 'Youth Pemalang',
            'birth_date' => '2002-01-10',
            'phone' => '081234567890',
            'administrative_area_id' => AdministrativeArea::where('area_level', 'district')->firstOrFail()->uuid(),
        ])->assertRedirect(route('youth.identity-verification'));
    }

    private function verifiedIdentity(User $user): void
    {
        $user->identity()->updateOrCreate([], ['verification_status' => UserIdentity::STATUS_VERIFIED]);
    }

    public function test_unverified_email_and_incomplete_biodata_cannot_skip_ahead(): void
    {
        $unverified = $this->youth(['email_verified_at' => null]);
        $this->actingAs($unverified)->get(route('youth.biodata.edit'))->assertRedirect(route('verification.notice'));

        $user = $this->youth();
        $this->actingAs($user)->get(route('youth.biodata.edit'))->assertOk();
        $this->actingAs($user)->get(route('youth.identity-verification'))->assertRedirect(route('youth.onboarding'));
        $this->actingAs($user)->get(route('youth.profile.edit'))->assertRedirect(route('youth.onboarding'));
        $this->actingAs($user)->get(route('youth.skills.edit'))->assertRedirect(route('youth.onboarding'));
        $this->actingAs($user)->get(route('youth.account.show'))->assertOk()->assertSee($user->email);
        $this->actingAs($user)->get('/')->assertOk();
        $this->actingAs($user)->get('/activities')->assertOk();
    }

    public function test_biodata_is_private_and_identity_pending_or_revision_blocks_profile(): void
    {
        $user = $this->youth();
        $this->biodata($user);
        $this->assertFalse($user->fresh()->profileVisibility->is_profile_public);
        $this->actingAs($user)->get(route('youth.identity-verification'))->assertOk();

        foreach ([UserIdentity::STATUS_PENDING, UserIdentity::STATUS_REVISION] as $status) {
            $user->identity()->update(['verification_status' => $status]);
            $this->actingAs($user)->get(route('youth.profile.edit'))->assertRedirect(route('youth.onboarding'));
        }

        $this->verifiedIdentity($user);
        $this->actingAs($user)->get(route('youth.profile.edit'))->assertOk();
        $this->actingAs($user)->get(route('youth.skills.edit'))->assertRedirect(route('youth.onboarding'));
    }

    public function test_profile_and_portfolio_baseline_unlock_participation_and_relock_when_data_is_removed(): void
    {
        $user = $this->youth();
        $this->biodata($user);
        $this->verifiedIdentity($user);
        $this->actingAs($user)->put(route('youth.profile.update'), [
            'bio' => 'Saya ingin belajar dan berkontribusi di Pemalang.',
            'occupation_status' => 'student',
        ])->assertRedirect(route('youth.profile.show'));

        $this->actingAs($user)->get(route('youth.skills.edit'))->assertOk();
        $this->actingAs($user)->get(route('youth.communities.index'))->assertRedirect(route('youth.onboarding'));
        app(SyncUserSkillsAction::class)->execute($user, [Skill::firstOrFail()->uuid()]);
        $this->assertFalse(app(YouthOnboardingService::class)->state($user)['onboardingComplete']);
        app(SyncUserInterestsAction::class)->execute($user, [Interest::firstOrFail()->uuid()]);
        $this->assertTrue(app(YouthOnboardingService::class)->state($user)['onboardingComplete']);
        $this->actingAs($user)->get(route('youth.communities.index'))->assertOk();

        app(SyncUserSkillsAction::class)->execute($user, []);
        $this->assertFalse(app(YouthOnboardingService::class)->state($user)['onboardingComplete']);
        $this->actingAs($user)->get(route('youth.communities.index'))->assertRedirect(route('youth.onboarding'));
        $user->profile()->update(['bio' => null]);
        $this->actingAs($user)->get(route('youth.skills.edit'))->assertRedirect(route('youth.onboarding'));
    }

    public function test_google_only_account_can_create_password_and_existing_password_requires_current_password(): void
    {
        $user = $this->youth(['password' => null]);
        $this->actingAs($user)->get(route('youth.account.security'))->assertOk()->assertSee('Buat Password');
        $this->actingAs($user)->put(route('youth.account.security.update'), [
            'password' => 'new-password-123', 'password_confirmation' => 'new-password-123',
        ])->assertRedirect(route('youth.account.security'));
        $this->assertTrue(Hash::check('new-password-123', $user->fresh()->password));

        $this->actingAs($user)->put(route('youth.account.security.update'), [
            'current_password' => 'wrong', 'password' => 'another-password-123',
            'password_confirmation' => 'another-password-123',
        ])->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('new-password-123', $user->fresh()->password));

        $this->actingAs($user)->put(route('youth.account.security.update'), [
            'current_password' => 'new-password-123', 'password' => 'another-password-123',
            'password_confirmation' => 'another-password-123',
        ])->assertRedirect(route('youth.account.security'));
        $this->assertTrue(Hash::check('another-password-123', $user->fresh()->password));
    }

    public function test_account_routes_are_youth_only_and_email_stays_off_public_pages(): void
    {
        $youth = $this->youth(['email' => 'private-youth@example.test']);
        $admin = User::factory()->create()->assignRole('admin');
        $verifier = User::factory()->create()->assignRole('verifier');

        $this->actingAs($admin)->get(route('youth.account.show'))->assertForbidden();
        $this->actingAs($verifier)->get(route('youth.account.security'))->assertForbidden();
        $this->actingAs($youth)->get(route('youth.account.show'))->assertSee($youth->email);
        $this->get('/')->assertDontSee($youth->email);
    }
}
