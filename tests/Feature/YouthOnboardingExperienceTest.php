<?php

namespace Tests\Feature;

use App\Actions\Youth\UpsertEducationAction;
use App\Actions\Youth\UpsertOrganizationExperienceAction;
use App\Models\PortfolioEvidence;
use App\Models\User;
use App\Services\Youth\YouthOnboardingService;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\YouthFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class YouthOnboardingExperienceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([RolePermissionSeeder::class, YouthFoundationSeeder::class]);
        Storage::fake('portfolio_evidence');
    }

    public function test_timeline_sidebar_and_theme_are_factual_and_gated(): void
    {
        $user = User::factory()->create()->assignRole('youth');
        $state = app(YouthOnboardingService::class)->state($user);
        $this->assertSame('biodata', $state['currentStep']);
        $this->assertContains('email', $state['completedSteps']);
        $this->assertContains('identity', $state['lockedSteps']);

        $this->actingAs($user)->get(route('youth.onboarding'))->assertOk()
            ->assertSee('Alur Aktivasi Akun')->assertSee('Langkah aktif')->assertSee('Terkunci');
        $this->actingAs($user)->get(route('youth.biodata.edit'))->assertOk()
            ->assertSee('biodataStepper')->assertSee('Konfirmasi')->assertSee('theme-toggle');
        $this->actingAs($user)->get(route('youth.profile.edit'))->assertRedirect(route('youth.onboarding'));
        $this->actingAs($user)->get(route('youth.home'))->assertSee('Persiapan Akun')->assertSee('workspace-group-toggle');
        $this->get('/')->assertOk()->assertSee('sipora.theme');
    }

    public function test_private_evidence_is_owner_only_and_never_verifies_the_claim(): void
    {
        $owner = $this->readyYouth();
        $other = $this->readyYouth();
        $education = app(UpsertEducationAction::class)->execute($owner, ['education_level' => 'sma_smk', 'institution_name' => 'Sekolah Pemalang']);
        $url = route('youth.portfolio.evidence.store', ['education', $education->uuid()]);
        $this->actingAs($owner)->post($url, ['evidence' => UploadedFile::fake()->image('ijazah.png')])->assertRedirect();

        $evidence = PortfolioEvidence::firstOrFail();
        Storage::disk('portfolio_evidence')->assertExists($evidence->file_path);
        $this->assertSame('education', $evidence->record_type);
        $this->assertSame(16, strlen($evidence->record_id));
        $this->assertArrayNotHasKey('file_path', $evidence->toArray());
        $this->actingAs($owner)->get(route('youth.portfolio.evidence.show', ['education', $education->uuid()]))
            ->assertOk()->assertSee('Self-reported')->assertDontSee($evidence->file_path);
        $this->actingAs($owner)->get(route('youth.portfolio.evidence.file', ['education', $education->uuid()]))
            ->assertOk()->assertHeader('Content-Type', 'image/png');
        $this->actingAs($other)->get(route('youth.portfolio.evidence.file', ['education', $education->uuid()]))->assertNotFound();
        $this->get(route('portfolio.show', $owner->profile->public_slug))->assertDontSee($evidence->file_path);
        $this->actingAs($owner)->delete(route('youth.educations.destroy', $education->uuid()))->assertRedirect();
        $this->assertDatabaseCount('portfolio_evidences', 0);
        Storage::disk('portfolio_evidence')->assertMissing($evidence->file_path);
    }

    public function test_invalid_files_and_cross_user_upload_are_rejected(): void
    {
        $owner = $this->readyYouth();
        $other = $this->readyYouth();
        $experience = app(UpsertOrganizationExperienceAction::class)->execute($owner, ['organization_name' => 'Organisasi Pemalang']);
        $url = route('youth.portfolio.evidence.store', ['organization', $experience->uuid()]);
        $this->actingAs($other)->post($url, ['evidence' => UploadedFile::fake()->image('proof.png')])->assertNotFound();
        $this->actingAs($owner)->post($url, ['evidence' => UploadedFile::fake()->create('script.exe', 20, 'application/x-msdownload')])->assertSessionHasErrors('evidence');
        $this->actingAs($owner)->post($url, ['evidence' => UploadedFile::fake()->image('huge.png')->size(6000)])->assertSessionHasErrors('evidence');
        $this->assertDatabaseCount('portfolio_evidences', 0);
    }

    public function test_external_certificate_stays_self_reported_and_private(): void
    {
        $owner = $this->readyYouth();
        $other = $this->readyYouth();
        $this->actingAs($owner)->post(route('youth.portfolio.external-certificates.store'), [
            'name' => 'Kursus Desain', 'issuer_name' => 'Lembaga Lokal', 'issued_at' => '2026-01-02',
            'evidence' => UploadedFile::fake()->image('sertifikat.png'),
        ])->assertRedirect();
        $certificate = $owner->certificates()->where('source_type', 'external')->firstOrFail();
        $this->assertSame('self_reported', $certificate->verification_status);
        $this->assertNull($certificate->verification_code);
        $this->assertArrayNotHasKey('file_path', $certificate->toArray());
        $this->actingAs($other)->get(route('youth.portfolio.external-certificates.file', $certificate))->assertNotFound();
        $this->actingAs($owner)->get(route('youth.portfolio.external-certificates.file', $certificate))->assertOk();
    }

    private function readyYouth(): User
    {
        $user = User::factory()->create()->assignRole('youth');
        $this->completeYouthOnboarding($user, 'profile');

        return $user;
    }
}
