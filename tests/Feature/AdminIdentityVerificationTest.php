<?php

namespace Tests\Feature;

use App\Actions\Youth\SubmitIdentityVerificationAction;
use App\Models\AuditEntry;
use App\Models\User;
use App\Models\UserIdentityVerification;
use App\Support\BinaryUuid;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminIdentityVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        Storage::fake('private');
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create()->assignRole($role);

        return $role === 'youth' ? $this->completeYouthOnboarding($user, 'biodata') : $user;
    }

    private function pendingSubmission(?User $youth = null): UserIdentityVerification
    {
        $youth ??= $this->userWithRole('youth');

        return app(SubmitIdentityVerificationAction::class)->execute($youth, [
            'document_type' => 'ktp',
            'document_number' => '3327012345678901',
        ], UploadedFile::fake()->image('identity.jpg'));
    }

    public function test_only_admin_can_access_queue_and_detail(): void
    {
        $submission = $this->pendingSubmission();
        $admin = $this->userWithRole('admin');
        $youth = $this->userWithRole('youth');
        $verifier = $this->userWithRole('verifier');

        $this->get('/admin/identity-verifications')->assertRedirect('/login');
        $this->actingAs($youth)->get('/admin/identity-verifications')->assertForbidden();
        $this->actingAs($verifier)->get('/admin/identity-verifications')->assertForbidden();
        $this->actingAs($admin)->get('/admin/identity-verifications')
            ->assertOk()->assertSee('Antrean Verifikasi Identitas');
        $this->actingAs($admin)->get('/admin/identity-verifications/'.$submission->uuid())
            ->assertOk()->assertSee('Tinjau Identitas Pemuda');
        $this->actingAs($admin)->get('/admin/identity-verifications/not-a-uuid')->assertNotFound();
    }

    public function test_private_document_is_streamed_only_to_admin_without_exposing_path(): void
    {
        $owner = $this->userWithRole('youth');
        $otherYouth = $this->userWithRole('youth');
        $verifier = $this->userWithRole('verifier');
        $admin = $this->userWithRole('admin');
        $submission = $this->pendingSubmission($owner);
        $url = '/admin/identity-verifications/'.$submission->uuid().'/document';

        $this->get($url)->assertRedirect('/login');
        $this->actingAs($owner)->get($url)->assertForbidden();
        $this->actingAs($otherYouth)->get($url)->assertForbidden();
        $this->actingAs($verifier)->get($url)->assertForbidden();
        $response = $this->actingAs($admin)->get($url)->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->assertStringNotContainsString($submission->document_path, $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
    }

    public function test_admin_can_approve_with_audit_notification_and_binary_reviewer_fk(): void
    {
        $submission = $this->pendingSubmission();
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)->post('/admin/identity-verifications/'.$submission->uuid().'/review', [
            'decision' => 'verified',
        ])->assertRedirect('/admin/identity-verifications/'.$submission->uuid());

        $submission->refresh();
        $identity = $submission->userIdentity->fresh();
        $this->assertSame('verified', $submission->status);
        $this->assertSame('verified', $identity->verification_status);
        $this->assertNotNull($submission->reviewed_at);
        $this->assertNotNull($identity->verified_at);
        $this->assertTrue(hash_equals($admin->getKey(), $submission->reviewed_by));
        $this->assertSame(16, strlen(DB::table('user_identity_verifications')->value('reviewed_by')));

        $notification = $identity->user->siporaNotifications()->firstOrFail();
        $this->assertSame('identity_verification_result', $notification->notification_type);
        $this->assertSame('verified', $notification->data['status']);
        $this->assertDatabaseHas('activity_log', ['event' => 'identity_verified']);
        $this->actingAs($identity->user)->get('/youth/identity-verification')
            ->assertOk()->assertSee('Identitas terverifikasi');
    }

    public function test_revision_requires_reason_updates_state_and_notifies_youth(): void
    {
        $submission = $this->pendingSubmission();
        $admin = $this->userWithRole('admin');
        $url = '/admin/identity-verifications/'.$submission->uuid().'/review';

        $this->actingAs($admin)->post($url, ['decision' => 'revision'])
            ->assertSessionHasErrors('review_notes');
        $this->actingAs($admin)->post($url, [
            'decision' => 'revision', 'review_notes' => 'Foto dokumen buram, mohon unggah ulang.',
        ])->assertRedirect();

        $this->assertSame('revision', $submission->fresh()->status);
        $this->assertSame('revision', $submission->userIdentity->fresh()->verification_status);
        $this->assertDatabaseHas('activity_log', ['event' => 'identity_revision_requested']);
        $this->assertSame('revision', $submission->userIdentity->user->siporaNotifications()->firstOrFail()->data['status']);
    }

    public function test_rejection_requires_reason_and_does_not_suspend_account(): void
    {
        $submission = $this->pendingSubmission();
        $admin = $this->userWithRole('admin');
        $url = '/admin/identity-verifications/'.$submission->uuid().'/review';

        $this->actingAs($admin)->post($url, ['decision' => 'rejected'])
            ->assertSessionHasErrors('review_notes');
        $this->actingAs($admin)->post($url, [
            'decision' => 'rejected', 'review_notes' => 'Dokumen tidak sesuai dengan data pemohon.',
        ])->assertRedirect();

        $this->assertSame('rejected', $submission->fresh()->status);
        $this->assertSame('rejected', $submission->userIdentity->fresh()->verification_status);
        $this->assertNotNull($submission->userIdentity->user->fresh());
        $this->assertDatabaseHas('activity_log', ['event' => 'identity_rejected']);
    }

    public function test_invalid_transition_duplicate_decision_and_stale_review_are_blocked(): void
    {
        $submission = $this->pendingSubmission();
        $admin = $this->userWithRole('admin');
        $url = '/admin/identity-verifications/'.$submission->uuid().'/review';

        $this->actingAs($admin)->post($url, ['decision' => 'unknown'])
            ->assertSessionHasErrors('decision');
        $this->actingAs($admin)->post($url, ['decision' => 'verified'])->assertRedirect();
        $this->actingAs($admin)->post($url, ['decision' => 'verified'])
            ->assertSessionHasErrors('decision');
        $this->assertSame(1, AuditEntry::where('event', 'identity_verified')->count());

        $first = $this->pendingSubmission();
        $newer = UserIdentityVerification::create([
            'id' => BinaryUuid::generate(),
            'user_identity_id' => $first->user_identity_id,
            'document_type' => 'ktp',
            'document_path' => 'identity-documents/newer.jpg',
            'status' => 'pending',
            'submitted_at' => now()->addSecond(),
            'metadata' => ['source' => 'test'],
        ]);
        Storage::disk('private')->put($newer->document_path, 'test');

        $this->actingAs($admin)->post('/admin/identity-verifications/'.$first->uuid().'/review', [
            'decision' => 'verified',
        ])->assertSessionHasErrors('decision');
        $this->assertSame('pending', $first->fresh()->status);
    }

    public function test_sensitive_values_are_absent_from_decision_audit_and_notification(): void
    {
        $submission = $this->pendingSubmission();
        $admin = $this->userWithRole('admin');
        $this->actingAs($admin)->post('/admin/identity-verifications/'.$submission->uuid().'/review', [
            'decision' => 'revision', 'review_notes' => 'Mohon perbaiki dokumen.',
        ])->assertRedirect();

        $auditPayload = AuditEntry::where('event', 'identity_revision_requested')->firstOrFail()->properties->toJson();
        $notificationPayload = $submission->userIdentity->user->siporaNotifications()->firstOrFail()->toJson();
        foreach (['3327012345678901', $submission->document_path, $submission->document_number_hash] as $sensitive) {
            $this->assertStringNotContainsString($sensitive, $auditPayload);
            $this->assertStringNotContainsString($sensitive, $notificationPayload);
        }
    }
}
