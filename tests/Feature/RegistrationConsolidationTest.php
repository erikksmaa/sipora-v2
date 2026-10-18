<?php

namespace Tests\Feature;

use App\Models\AdministrativeArea;
use App\Models\Interest;
use App\Models\User;
use App\Models\UserIdentityVerification;
use App\Services\Youth\IdentityAnchorService;
use App\Support\BinaryUuid;
use App\Support\Nik;
use Carbon\CarbonImmutable;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegistrationConsolidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        config(['recaptcha.enabled' => false]);
        Notification::fake();
        Storage::fake('private');
    }

    public function test_registration_requires_numeric_unique_nik_and_privacy_safe_errors(): void
    {
        $data = $this->registrationPayload();
        foreach ([null, '123', '123456789012345x'] as $nik) {
            $this->postJson('/register', array_replace($data, ['national_id' => $nik]))->assertUnprocessable()->assertJsonValidationErrors('national_id');
        }
        $this->post('/register', $this->registrationSteps($data))->assertRedirect(route('verification.notice'));
        $user = User::where('email', $data['email'])->firstOrFail();
        $identity = $user->identity;
        $this->assertSame(hash('sha256', $data['national_id']), $identity->national_id_hash);
        $this->assertNotSame($data['national_id'], $identity->national_id_ciphertext);
        $this->assertSame($data['national_id'], Crypt::decryptString($identity->national_id_ciphertext));
        $this->assertSame('unverified', $identity->verification_status);
        $this->assertFalse($user->hasVerifiedEmail());
        $this->post('/logout');
        $response = $this->postJson('/register', array_replace($data, ['email' => 'other@example.test']));
        $response->assertUnprocessable()->assertJsonValidationErrors('national_id')->assertSee(Nik::DUPLICATE)->assertDontSee($data['email'])->assertDontSee($user->name)->assertDontSee($data['national_id']);
        $this->assertDatabaseCount('users', 1);
        $this->get('/register')->assertDontSee($data['national_id']);
        $this->assertStringNotContainsString($data['national_id'], $identity->toJson());
        $this->assertSame(1, $user->interests()->count());
        $this->assertSame($data['village_id'], $user->primaryDomicile->village->uuid());
        $this->assertFalse($user->profileVisibility->is_profile_public);
    }

    public function test_address_membership_gender_age_and_interest_limits_are_server_validated(): void
    {
        $data = $this->registrationPayload();
        $other = AdministrativeArea::where('area_level', 'district')->where('id', '!=', BinaryUuid::bytes($data['administrative_area_id']))->first();
        $this->postJson('/register', array_replace($data, ['administrative_area_id' => $other->uuid()]))->assertJsonValidationErrors('village_id');
        $this->postJson('/register', array_replace($data, ['gender' => 'other']))->assertJsonValidationErrors('gender');
        $this->postJson('/register', array_replace($data, ['birth_date' => now()->subYears(10)->toDateString()]))->assertJsonValidationErrors('birth_date');
        $this->postJson('/register', array_replace($data, ['interests' => Interest::take(6)->get()->map->uuid()->all()]))->assertJsonValidationErrors('interests');
        $this->postJson('/register', array_replace($data, ['interests' => []]))->assertJsonValidationErrors('interests');
        $this->assertDatabaseCount('users', 0);
    }

    public function test_manual_dob_difference_is_allowed_and_registration_rolls_back_all_writes(): void
    {
        $data = $this->registrationPayload(['birth_date' => now()->subYears(22)->toDateString()]);
        $this->app->instance(IdentityAnchorService::class, new class
        {
            public function assign($user, $nik)
            {
                throw new \RuntimeException('Fixture write failed');
            }
        });
        $this->withoutExceptionHandling();
        try {
            $this->post('/register', $this->registrationSteps($data));
            $this->fail('Expected fixture failure');
        } catch (\RuntimeException $e) {
            $this->assertSame('Fixture write failed', $e->getMessage());
        }
        $this->app->instance(IdentityAnchorService::class, new IdentityAnchorService);
        foreach (['users', 'user_profiles', 'user_addresses', 'user_identities', 'user_interests'] as $table) {
            $this->assertDatabaseCount($table, 0);
        }
        $this->post('/register', $this->registrationSteps($data))->assertRedirect(route('verification.notice'));
    }

    public function test_new_evidence_types_use_anchor_without_document_number_and_keep_files_private(): void
    {
        foreach (['ktp', 'kia', 'student_card'] as $type) {
            $user = $this->completeYouthOnboarding(User::factory()->create()->assignRole('youth'), 'biodata');
            $hash = $user->identity()->value('national_id_hash');
            $this->actingAs($user)->post(route('youth.identity-verification.store'), ['document_type' => $type, 'acknowledgement' => 1, 'document_file' => UploadedFile::fake()->image('evidence.png')])->assertRedirect(route('youth.onboarding'));
            $submission = $user->fresh()->identity->latestVerification;
            $this->assertSame($hash, $submission->document_number_hash);
            $this->assertNull($submission->document_number_ciphertext);
            Storage::disk('private')->assertExists($submission->document_path);
            Storage::disk('public')->assertMissing($submission->document_path);
            $this->get(route('youth.identity-verification'))->assertDontSee('name="document_number"', false)->assertDontSee($user->identity->nationalId());
        }
    }

    public function test_legacy_evidence_and_broader_gender_render_without_leaking_full_number(): void
    {
        $user = $this->completeYouthOnboarding(User::factory()->create()->assignRole('youth'), 'biodata');
        $user->profile->update(['gender' => 'other']);
        $response = $this->actingAs($user)->get(route('youth.biodata.edit'))->assertOk()->assertDontSee($user->identity->nationalId());
        preg_match('/<select[^>]*name="gender"[^>]*>(.*?)<\/select>/s', $response->getContent(), $gender);
        $this->assertStringNotContainsString('value="other"', $gender[1]);
        $identity = $user->identity;
        $submission = UserIdentityVerification::create(['user_identity_id' => $identity->getKey(), 'document_type' => 'student_card', 'document_number_ciphertext' => Crypt::encryptString('LEGACY-STUDENT-001'), 'document_number_hash' => hash('sha256', 'LEGACY-STUDENT-001'), 'document_path' => 'legacy.pdf', 'status' => 'pending', 'submitted_at' => now()]);
        $admin = User::factory()->create()->assignRole('admin');
        $this->actingAs($admin)->get(route('admin.identity-verifications.show', $submission))->assertOk()->assertDontSee('LEGACY-STUDENT-001')->assertSee('************');
        $this->assertSame('LEGACY-STUDENT-001', $submission->documentNumber());
        $this->post(route('admin.identity-verifications.review', $submission), ['decision' => 'verified'])->assertRedirect();
        $this->assertSame('verified', $identity->fresh()->verification_status);
    }

    public function test_nik_parser_is_date_only_and_uses_current_youth_age_range(): void
    {
        $today = CarbonImmutable::parse('2026-10-18');
        $this->assertSame('2005-01-01', Nik::birthDate('0000000101050001', $today));
        $this->assertSame('2005-01-01', Nik::birthDate('0000004101050001', $today));
        $this->assertNull(Nik::birthDate('0000003111050001', $today));
        $this->assertNull(Nik::birthDate('0000000101500001', $today));
        $this->assertSame('2010-10-18', Nik::birthDate('0000001810100001', $today));
        $this->assertSame('1996-10-19', Nik::birthDate('0000001910960001', $today));
    }

    public function test_google_or_legacy_account_can_complete_nik_village_and_interests_before_evidence(): void
    {
        $data = $this->registrationPayload();
        $user = User::factory()->create()->assignRole('youth');
        $this->actingAs($user)->put(route('youth.biodata.update'), [
            'full_name' => 'Youth Pelengkapan', 'national_id' => $data['national_id'], 'birth_date' => $data['birth_date'],
            'gender' => 'female', 'phone' => '081234567890', 'administrative_area_id' => $data['administrative_area_id'],
            'village_id' => $data['village_id'], 'interests' => $data['interests'],
        ])->assertRedirect(route('youth.identity-verification'));
        $fresh = $user->fresh();
        $this->assertSame($data['national_id'], $fresh->identity->nationalId());
        $this->assertSame('unverified', $fresh->identity->verification_status);
        $this->assertSame(1, $fresh->interests()->count());
        $this->assertSame($data['village_id'], $fresh->primaryDomicile->village->uuid());
        $this->get(route('youth.identity-verification'))->assertOk()->assertDontSee('name="document_number"', false);
    }

    public function test_local_reference_has_canonical_districts_and_all_villages(): void
    {
        $this->registrationPayload();
        $this->assertSame(14, AdministrativeArea::where('area_level', 'district')->count());
        $this->assertSame(223, AdministrativeArea::where('area_level', 'village')->count());
        $this->get('/register')->assertOk()->assertSee('name="national_id"', false)->assertSee('Pilih jenis kelamin')->assertDontSee('value="other"', false)->assertSee('Kirim pendaftaran');
    }
}
