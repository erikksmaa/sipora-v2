<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\ActivityParticipation;
use App\Models\AdministrativeArea;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationMembership;
use App\Models\Skill;
use App\Models\User;
use App\Services\Youth\YouthOnboardingService;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\YouthEnrichmentSeeder;
use Database\Seeders\YouthFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class YouthExperienceCorrectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('recaptcha.enabled', false);
        $this->seed([RolePermissionSeeder::class, YouthFoundationSeeder::class, YouthEnrichmentSeeder::class]);
        Storage::fake('private');
        Storage::fake('portfolio_evidence');
    }

    public function test_email_youth_moves_through_activation_and_private_portfolio(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Pemuda Alur', 'email' => 'alur@example.test',
            'password' => 'abcdefgh', 'password_confirmation' => 'abcdefgh',
        ])->assertRedirect(route('verification.notice'));
        $youth = User::where('email', 'alur@example.test')->firstOrFail();
        $this->assertFalse($youth->hasVerifiedEmail());
        $this->get(route('youth.biodata.edit'))->assertRedirect(route('verification.notice'));

        $verifyUrl = URL::temporarySignedRoute('verification.verify', now()->addHour(), [
            'id' => $youth->uuid(), 'hash' => sha1($youth->email),
        ]);
        $this->get($verifyUrl)->assertRedirect();
        $this->assertTrue($youth->fresh()->hasVerifiedEmail());

        $this->put(route('youth.biodata.update'), [
            'full_name' => 'Pemuda Alur', 'birth_date' => '2002-02-02',
            'administrative_area_id' => AdministrativeArea::where('area_level', 'district')->firstOrFail()->uuid(),
            'address_line' => 'Alamat privat contoh', 'phone' => '081234567890',
            'instagram' => '@pemuda.alur', 'facebook' => 'pemuda.alur',
            'linkedin' => 'https://www.linkedin.com/in/pemuda-alur',
        ])->assertRedirect(route('youth.identity-verification'));
        $this->assertSame('pemuda.alur', $youth->fresh()->contactLinks->instagram);
        $this->get(route('youth.profile.edit'))->assertRedirect(route('youth.onboarding'));

        $this->post(route('youth.identity-verification.store'), [
            'document_type' => 'ktp', 'document_number' => '3327012345678901',
            'document_file' => UploadedFile::fake()->image('ktp.png'),
        ])->assertRedirect(route('youth.onboarding'));
        $submission = $youth->fresh()->identity->verifications()->firstOrFail();
        Storage::disk('private')->assertExists($submission->document_path);
        $this->get(route('youth.portfolio.show'))->assertRedirect(route('youth.onboarding'));

        $admin = User::factory()->create()->assignRole('admin');
        $this->actingAs($admin)->post('/admin/identity-verifications/'.$submission->uuid().'/review', [
            'decision' => 'verified',
        ])->assertRedirect();
        $this->assertSame('verified', $youth->fresh()->identity->verification_status);
        $this->assertSame('profile', app(YouthOnboardingService::class)->state($youth->fresh())['currentStep']);
        $youth = $youth->fresh();
        $this->actingAs($youth)->get(route('youth.profile.edit'))->assertOk();
        $this->put(route('youth.profile.update'), [
            'bio' => 'Aktif belajar dan berkarya di Pemalang.', 'occupation_status' => 'student',
        ])->assertRedirect();
        $this->put(route('youth.skills.update'), ['custom_skill' => 'Teknik Drone'])->assertRedirect(route('youth.skills.edit'));
        $this->put(route('youth.interests.update'), ['custom_interest' => 'Pemetaan Desa'])->assertRedirect(route('youth.interests.edit'));
        $this->get(route('youth.activities.index'))->assertOk();
        $manager = User::factory()->create()->assignRole('youth');
        $communityCategory = OrganizationCategory::create(['name' => 'Kategori Alur', 'slug' => 'kategori-alur']);
        $community = Organization::create([
            'created_by_user_id' => $manager->getKey(), 'category_id' => $communityCategory->getKey(),
            'name' => 'Komunitas Alur', 'slug' => 'komunitas-alur', 'description' => 'Komunitas uji alur Youth.',
            'social_links' => (object) [], 'review_status' => Organization::REVIEW_APPROVED,
            'operational_status' => Organization::OPERATIONAL_ACTIVE, 'approved_at' => now(),
        ]);
        foreach ([[$manager, OrganizationMembership::ROLE_LEADER], [$youth, OrganizationMembership::ROLE_MEMBER]] as [$member, $role]) {
            OrganizationMembership::create([
                'organization_id' => $community->getKey(), 'user_id' => $member->getKey(),
                'access_role' => $role, 'membership_status' => OrganizationMembership::STATUS_ACTIVE,
                'requested_at' => now(), 'approved_at' => now(), 'approved_by' => $manager->getKey(),
            ]);
        }
        $activityCategory = ActivityCategory::firstOrCreate(['slug' => 'kategori-alur'], ['name' => 'Kategori Alur']);
        $activity = Activity::create([
            'organization_id' => $community->getKey(), 'category_id' => $activityCategory->getKey(),
            'created_by_user_id' => $manager->getKey(), 'title' => 'Activity Alur', 'slug' => 'activity-alur',
            'description' => 'Activity publik untuk uji alur Youth.', 'location_type' => 'offline',
            'venue_name' => 'Gedung Pemuda', 'start_at' => now()->addDays(5),
            'end_at' => now()->addDays(5)->addHours(2), 'registration_open_at' => now()->subDay(),
            'registration_close_at' => now()->addDays(3), 'quota' => 20, 'registration_mode' => 'open',
            'review_status' => Activity::REVIEW_APPROVED, 'publication_status' => Activity::PUBLICATION_PUBLISHED,
            'execution_status' => Activity::EXECUTION_SCHEDULED, 'published_at' => now(),
        ]);
        $this->get(route('activities.show', $activity))->assertOk();
        $this->post(route('activities.register', $activity))->assertRedirect();
        $this->assertSame(ActivityParticipation::REGISTRATION_ACCEPTED,
            $youth->activityParticipations()->where('activity_id', $activity->getKey())->firstOrFail()->registration_status);

        $this->post(route('youth.educations.store'), [
            'education_level' => 'sma_smk', 'institution_name' => 'Sekolah Pemalang',
        ])->assertRedirect();
        $this->post(route('youth.organization-experiences.store'), [
            'organization_name' => 'Forum Pemuda', 'role_title' => 'Koordinator',
        ])->assertRedirect();
        $experience = $youth->organizationExperiences()->firstOrFail();
        $this->post(route('youth.portfolio.evidence.store', ['organization', $experience->uuid()]), [
            'evidence' => UploadedFile::fake()->image('bukti.png'),
        ])->assertRedirect();
        $this->post(route('youth.achievements.store'), ['title' => 'Juara Inovasi'])->assertRedirect();
        $achievement = $youth->achievements()->where('title', 'Juara Inovasi')->firstOrFail();
        $this->post(route('youth.portfolio.evidence.store', ['achievement', $achievement->uuid()]), [
            'evidence' => UploadedFile::fake()->image('penghargaan.png'),
        ])->assertRedirect();
        Storage::disk('portfolio_evidence')->assertExists($achievement->fresh()->evidence_path);
        $this->post(route('youth.portfolio.external-certificates.store'), [
            'name' => 'Pelatihan Pemetaan', 'issuer_name' => 'Forum Pemuda', 'issued_at' => '2025-01-15',
        ])->assertRedirect();
        $this->assertNull($youth->certificates()->where('name', 'Pelatihan Pemetaan')->firstOrFail()->file_path);

        $owner = $this->get(route('youth.portfolio.show'))->assertOk()
            ->assertSee('Sekolah Pemalang')->assertSee('Forum Pemuda')->assertSee('Juara Inovasi')
            ->assertSee('Teknik Drone')->assertSee('Pemetaan Desa');
        $owner->assertDontSee('Alamat privat contoh')->assertDontSee('pemuda.alur');
        $this->get(route('notifications.index'))->assertOk()->assertSee('Notifikasi');
        $this->put(route('youth.account.security.update'), [
            'current_password' => 'abcdefgh', 'password' => '12345678', 'password_confirmation' => '12345678',
        ])->assertRedirect();
        $this->assertTrue(Hash::check('12345678', $youth->fresh()->password));

        $this->get('/')->assertOk()->assertSee('Notifikasi')->assertSee('logo.png');
        $this->assertDatabaseCount('user_custom_portfolio_tags', 2);
    }

    public function test_custom_values_validate_and_google_account_can_add_local_password(): void
    {
        $youth = $this->completeYouthOnboarding(User::factory()->create(['password' => null])->assignRole('youth'), 'profile');
        $youth->socialAccounts()->create(['provider' => 'google', 'provider_user_id' => 'google-test-user', 'provider_email' => $youth->email]);
        $this->actingAs($youth)->get(route('youth.account.security'))->assertOk()->assertSee('Buat Password');
        $this->put(route('youth.skills.update'), ['other_selected' => '1'])->assertSessionHasErrors('custom_skill');
        $this->put(route('youth.skills.update'), ['custom_skill' => '<script>'])->assertSessionHasErrors('custom_skill');
        $existing = Skill::firstOrFail()->name;
        $this->put(route('youth.skills.update'), ['custom_skill' => $existing])->assertSessionHasErrors('custom_skill');
        $this->put(route('youth.skills.update'), ['custom_skill' => 'Teknik Drone'])->assertRedirect();
        $this->put(route('youth.skills.update'), ['custom_skill' => '  teknik   drone  '])->assertSessionHasErrors('custom_skill');
        $this->put(route('youth.interests.update'), ['other_selected' => '1'])->assertSessionHasErrors('custom_interest');
        $this->assertSame(1, $youth->customPortfolioTags()->count());
        $this->put(route('youth.account.security.update'), [
            'password' => '12345678', 'password_confirmation' => '12345678',
        ])->assertRedirect();
        $this->assertTrue(Hash::check('12345678', $youth->fresh()->password));
        $this->post(route('logout'))->assertRedirect();
        $this->post(route('login.store'), ['email' => $youth->email, 'password' => '12345678'])->assertRedirect();
        $this->assertTrue($youth->fresh()->socialAccounts()->where('provider', 'google')->exists());
    }

    public function test_identity_revision_keeps_profile_locked_until_resubmission_is_approved(): void
    {
        $youth = $this->completeYouthOnboarding(User::factory()->create()->assignRole('youth'), 'biodata');
        $this->actingAs($youth)->post(route('youth.identity-verification.store'), [
            'document_type' => 'ktp', 'document_number' => '3327012345678901',
            'document_file' => UploadedFile::fake()->image('first.png'),
        ])->assertRedirect();
        $first = $youth->fresh()->identity->verifications()->firstOrFail();
        $admin = User::factory()->create()->assignRole('admin');
        $this->actingAs($admin)->post('/admin/identity-verifications/'.$first->uuid().'/review', [
            'decision' => 'revision', 'review_notes' => 'Foto perlu diperjelas.',
        ])->assertRedirect();
        $this->assertSame('revision', $youth->fresh()->identity->verification_status);
        $this->assertSame('revision', $youth->siporaNotifications()->latest('created_at')->firstOrFail()->data['status']);
        $this->actingAs($youth->fresh())->get(route('youth.profile.edit'))->assertRedirect(route('youth.onboarding'));
        $this->post(route('youth.identity-verification.store'), [
            'document_type' => 'ktp', 'document_number' => '3327012345678901',
            'document_file' => UploadedFile::fake()->image('replacement.png'),
        ])->assertRedirect();
        $latest = $youth->fresh()->identity->verifications()->latest('created_at')->firstOrFail();
        $this->assertNotSame($first->getKey(), $latest->getKey());
        $this->actingAs($admin)->post('/admin/identity-verifications/'.$latest->uuid().'/review', [
            'decision' => 'verified',
        ])->assertRedirect();
        $this->actingAs($youth->fresh())->get(route('youth.profile.edit'))->assertOk();
    }
}
