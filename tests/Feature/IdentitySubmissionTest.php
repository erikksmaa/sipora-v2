<?php

namespace Tests\Feature;

use App\Models\AuditEntry;
use App\Models\User;
use App\Models\UserIdentity;
use App\Models\UserIdentityVerification;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class IdentitySubmissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        Storage::fake('private');
    }

    private function youth(): User
    {
        return $this->completeYouthOnboarding(User::factory()->create()->assignRole('youth'), 'biodata');
    }

    private function validSubmission(array $overrides = []): array
    {
        return array_replace([
            'document_type' => 'ktp',
            'document_number' => '3327012345678901',
            'document_file' => UploadedFile::fake()->image('identity.jpg'),
        ], $overrides);
    }

    public function test_youth_submits_identity_to_randomized_private_storage(): void
    {
        $user = $this->youth();

        $this->actingAs($user)->post('/youth/identity-verification', $this->validSubmission())
            ->assertRedirect();

        $identity = $user->fresh()->identity;
        $submission = $identity->verifications()->firstOrFail();
        $this->assertSame(UserIdentity::STATUS_PENDING, $identity->verification_status);
        $this->assertSame(UserIdentityVerification::STATUS_PENDING, $submission->status);
        $this->assertNotSame('identity.jpg', basename($submission->document_path));
        Storage::disk('private')->assertExists($submission->document_path);
        Storage::disk('public')->assertMissing($submission->document_path);
        $this->assertSame('3327012345678901', $submission->documentNumber());
        $this->assertArrayNotHasKey('original_filename', $submission->metadata);

        $audit = AuditEntry::where('event', 'identity_submitted')->firstOrFail();
        $encoded = json_encode($audit->properties->all());
        $this->assertStringNotContainsString('3327012345678901', $encoded);
        $this->assertStringNotContainsString($submission->document_path, $encoded);
    }

    public function test_student_card_is_not_saved_as_national_identity(): void
    {
        $user = $this->youth();
        $this->actingAs($user)->post('/youth/identity-verification', $this->validSubmission([
            'document_type' => 'student_card', 'document_number' => 'SISWA-2026-88',
        ]))->assertRedirect();

        $identity = $user->fresh()->identity;
        $this->assertSame('student_card', $identity->verification_method);
        $this->assertNull($identity->national_id_hash);
        $this->assertNull($identity->national_id_ciphertext);
    }

    public function test_identity_validation_rejects_type_mime_size_and_invalid_nik(): void
    {
        $user = $this->youth();
        $this->actingAs($user)->post('/youth/identity-verification', $this->validSubmission(['document_type' => 'passport']))
            ->assertSessionHasErrors('document_type');
        $this->actingAs($user)->post('/youth/identity-verification', $this->validSubmission([
            'document_number' => '123',
        ]))->assertSessionHasErrors('document_number');
        $this->actingAs($user)->post('/youth/identity-verification', $this->validSubmission([
            'document_file' => UploadedFile::fake()->create('identity.txt', 20, 'text/plain'),
        ]))->assertSessionHasErrors('document_file');
        $this->actingAs($user)->post('/youth/identity-verification', $this->validSubmission([
            'document_file' => UploadedFile::fake()->create('identity.pdf', 4097, 'application/pdf'),
        ]))->assertSessionHasErrors('document_file');
        $this->assertDatabaseCount('user_identity_verifications', 0);
    }

    public function test_duplicate_pending_submission_is_blocked_and_revision_can_resubmit(): void
    {
        $user = $this->youth();
        $this->actingAs($user)->post('/youth/identity-verification', $this->validSubmission())->assertRedirect();
        $first = $user->fresh()->identity->verifications()->firstOrFail();

        $this->actingAs($user)->post('/youth/identity-verification', $this->validSubmission())
            ->assertSessionHasErrors('document_file');
        $this->assertDatabaseCount('user_identity_verifications', 1);

        $first->update(['status' => 'revision', 'reviewed_at' => now(), 'review_notes' => 'Dokumen buram.']);
        $user->identity->update(['verification_status' => 'revision']);
        $this->actingAs($user)->post('/youth/identity-verification', $this->validSubmission([
            'document_file' => UploadedFile::fake()->image('replacement.png'),
        ]))->assertRedirect();

        $this->assertDatabaseCount('user_identity_verifications', 2);
        Storage::disk('private')->assertExists($first->document_path);
        $this->assertSame('pending', $user->fresh()->identity->verification_status);
    }

    public function test_verified_identity_cannot_be_overwritten_by_youth(): void
    {
        $user = $this->youth();
        $this->actingAs($user)->post('/youth/identity-verification', $this->validSubmission())->assertRedirect();
        $identity = $user->fresh()->identity;
        $identity->update(['verification_status' => 'verified', 'verified_at' => now()]);
        $identity->verifications()->update(['status' => 'verified', 'reviewed_at' => now()]);

        $this->actingAs($user)->post('/youth/identity-verification', $this->validSubmission())
            ->assertSessionHasErrors('document_file');
        $this->assertDatabaseCount('user_identity_verifications', 1);
    }

    public function test_submission_ownership_and_sensitive_serialization_are_safe(): void
    {
        $owner = $this->youth();
        $other = $this->youth();
        $this->actingAs($owner)->post('/youth/identity-verification', $this->validSubmission())->assertRedirect();
        $submission = $owner->fresh()->identity->verifications()->firstOrFail();

        $serialized = $submission->toArray();
        foreach (['document_number_hash', 'document_number_ciphertext', 'document_path', 'document_sha256'] as $sensitive) {
            $this->assertArrayNotHasKey($sensitive, $serialized);
        }
        $this->assertNull($other->fresh()->identity);
        $this->actingAs($other)->get('/youth/identity-verification')->assertOk();
        $this->assertDatabaseCount('user_identities', 2);
        $this->assertSame('unverified', $other->fresh()->identity->verification_status);
    }

    public function test_email_verification_alone_does_not_verify_sipora_identity(): void
    {
        $user = User::factory()->create();

        $this->assertTrue($user->hasVerifiedEmail());
        $this->assertNull($user->identity);
    }
}
