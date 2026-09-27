<?php

namespace Tests\Feature;

use App\Actions\Youth\SyncUserInterestsAction;
use App\Actions\Youth\UpdateDomicileAction;
use App\Actions\Youth\UpdateYouthProfileAction;
use App\Models\AdministrativeArea;
use App\Models\Interest;
use App\Models\User;
use App\Services\Youth\ProfileCompletionService;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\YouthFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class YouthProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([RolePermissionSeeder::class, YouthFoundationSeeder::class]);
    }

    public function test_youth_can_view_and_update_own_profile(): void
    {
        $user = $this->youth();
        $this->actingAs($user)->get('/youth/profile')->assertOk()->assertSee($user->name);

        $this->actingAs($user)->put('/youth/profile', $this->validProfile(['full_name' => 'Erik Kusuma']))
            ->assertRedirect('/youth/profile');

        $this->assertDatabaseHas('user_profiles', ['user_id' => $user->getKey(), 'full_name' => 'Erik Kusuma']);
        $this->assertSame('Erik Kusuma', $user->fresh()->name);
        $this->assertNotNull($user->fresh()->identity);
        $this->assertNotNull($user->fresh()->profileVisibility);
    }

    public function test_all_phase_two_stitch_based_pages_render_for_youth_only(): void
    {
        $youth = $this->youth();
        foreach (['/youth/home', '/youth/profile', '/youth/profile/edit', '/youth/onboarding', '/youth/interests', '/youth/identity-verification'] as $uri) {
            $this->actingAs($youth)->get($uri)->assertOk();
        }

        $admin = User::factory()->create();
        $admin->assignRole('admin');
        foreach (['/youth/home', '/youth/profile', '/youth/onboarding'] as $uri) {
            $this->actingAs($admin)->get($uri)->assertForbidden();
        }
    }

    public function test_invalid_profile_input_is_rejected(): void
    {
        $user = $this->youth();
        $this->actingAs($user)->from('/youth/profile/edit')->put('/youth/profile', $this->validProfile([
            'full_name' => 'A', 'birth_date' => '2099-01-01', 'phone' => 'not-a-phone',
        ]))->assertRedirect('/youth/profile/edit')->assertSessionHasErrors(['full_name', 'birth_date', 'phone']);
        $this->assertDatabaseCount('user_profiles', 0);
    }

    public function test_guest_and_other_user_cannot_update_a_profile(): void
    {
        $owner = $this->youth();
        $other = $this->youth();
        app(UpdateYouthProfileAction::class)->execute($owner, $this->validProfile(['full_name' => 'Owner Name']));

        $this->put('/youth/profile', $this->validProfile())->assertRedirect('/login');
        $this->actingAs($other)->put('/youth/profile', $this->validProfile(['full_name' => 'Other Name', 'user_id' => $owner->uuid()]));

        $this->assertSame('Owner Name', $owner->fresh()->profile->full_name);
        $this->assertSame('Other Name', $other->fresh()->profile->full_name);
    }

    public function test_onboarding_is_resumable(): void
    {
        $user = $this->youth();
        $this->actingAs($user)->get('/youth/onboarding')->assertOk()->assertSee('Profil dasar');
        app(UpdateYouthProfileAction::class)->execute($user, $this->validProfile());
        $this->actingAs($user->fresh())->get('/youth/onboarding')->assertOk()->assertSee('Domisili saat ini');
        app(UpdateDomicileAction::class)->execute($user, ['administrative_area_id' => AdministrativeArea::where('area_level', 'district')->firstOrFail()->uuid()]);
        $this->actingAs($user->fresh())->get('/youth/onboarding')->assertOk()->assertSee('Pilih minatmu');
        app(SyncUserInterestsAction::class)->execute($user, [Interest::firstOrFail()->uuid()]);
        $this->actingAs($user->fresh())->get('/youth/onboarding')->assertOk()->assertSee('Profil dasar siap');
    }

    public function test_interests_can_be_selected_updated_and_invalid_ids_are_rejected(): void
    {
        $user = $this->youth();
        $ids = Interest::take(2)->get()->map->uuid()->all();
        $this->actingAs($user)->put('/youth/interests', ['interests' => $ids])->assertRedirect('/youth/profile');
        $this->assertSame(2, $user->fresh()->interests()->count());

        $this->actingAs($user)->from('/youth/interests')->put('/youth/interests', ['interests' => ['018f7890-1234-7abc-8def-0123456789ab']])
            ->assertRedirect('/youth/interests')->assertSessionHasErrors('interests.0');
        $this->assertSame(2, $user->fresh()->interests()->count());
    }

    public function test_profile_completion_is_derived_and_age_does_not_block_access(): void
    {
        $user = $this->youth();
        $service = app(ProfileCompletionService::class);
        // Phase 3 weights: profile fields (50%) + domicile (10%) + interests (10%) + enrichment (30%)
        $this->assertSame(0, $service->calculate($user)['percentage']);
        app(UpdateYouthProfileAction::class)->execute($user, $this->validProfile());
        // full_name(10)+birth_date(10)+gender(5)+phone(5)+occupation_status(10)+bio(10) = 50
        $this->assertSame(50, $service->calculate($user->fresh())['percentage']);
        app(UpdateDomicileAction::class)->execute($user, ['administrative_area_id' => AdministrativeArea::where('area_level', 'district')->firstOrFail()->uuid()]);
        app(SyncUserInterestsAction::class)->execute($user, [Interest::firstOrFail()->uuid()]);
        // +domicile(10)+interests(10) = 70
        $this->assertSame(70, $service->calculate($user->fresh())['percentage']);
        // Access is not gated by completion percentage
        $this->actingAs($user)->get('/youth/home')->assertOk();
    }

    public function test_sensitive_fields_are_not_exposed_on_youth_home_or_profile_summary(): void
    {
        $user = $this->youth();
        app(UpdateYouthProfileAction::class)->execute($user, $this->validProfile(['phone' => '+628123456789', 'birth_date' => '1998-03-14']));
        app(UpdateDomicileAction::class)->execute($user, ['administrative_area_id' => AdministrativeArea::where('area_level', 'district')->firstOrFail()->uuid(), 'address_line' => 'Private Address 123']);
        foreach (['/youth/home', '/youth/profile'] as $uri) {
            $this->actingAs($user)->get($uri)->assertOk()->assertDontSee('+628123456789')->assertDontSee('1998-03-14')->assertDontSee('Private Address 123')->assertDontSee($user->email);
        }
    }

    public function test_binary_uuid_foreign_keys_round_trip(): void
    {
        $user = $this->youth();
        app(UpdateYouthProfileAction::class)->execute($user, $this->validProfile());
        app(UpdateDomicileAction::class)->execute($user, ['administrative_area_id' => AdministrativeArea::where('area_level', 'district')->firstOrFail()->uuid()]);
        app(SyncUserInterestsAction::class)->execute($user, [Interest::firstOrFail()->uuid()]);
        foreach (['user_profiles', 'user_addresses', 'user_interests', 'user_identities', 'user_profile_visibility'] as $table) {
            $this->assertSame(16, strlen(DB::table($table)->where('user_id', $user->getKey())->value('user_id')));
        }
    }

    public function test_profile_photo_is_public_and_invalid_files_are_rejected(): void
    {
        Storage::fake('public');
        $user = $this->youth();
        $this->actingAs($user)->put('/youth/profile', $this->validProfile(['profile_photo' => UploadedFile::fake()->image('avatar.jpg', 400, 400)]));
        Storage::disk('public')->assertExists($user->fresh()->profile->profile_photo_path);

        $this->actingAs($user)->from('/youth/profile/edit')->put('/youth/profile', $this->validProfile(['profile_photo' => UploadedFile::fake()->create('secret.pdf', 20, 'application/pdf')]))
            ->assertRedirect('/youth/profile/edit')->assertSessionHasErrors('profile_photo');
    }

    private function youth(): User
    {
        $user = User::factory()->create();
        $user->assignRole('youth');

        return $user;
    }

    private function validProfile(array $overrides = []): array
    {
        return array_merge(['full_name' => 'Youth Pemalang', 'birth_place' => 'Pemalang', 'birth_date' => '1998-03-14', 'gender' => 'male', 'phone' => '+628123456789', 'bio' => 'Aktif dan ingin berkembang bersama.', 'occupation_status' => 'worker', 'occupation_title' => 'Kreator'], $overrides);
    }
}
