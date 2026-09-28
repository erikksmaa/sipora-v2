<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationMembership;
use App\Models\OrganizationVerificationRequest;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\YouthFoundationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CommunityApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([RolePermissionSeeder::class, YouthFoundationSeeder::class]);
    }

    public function test_youth_can_create_a_draft_with_binary_uuid_relationships(): void
    {
        $youth = $this->youth();
        $category = $this->category();

        $this->actingAs($youth)->post(route('youth.communities.store'), $this->validData($category))
            ->assertRedirect();

        $community = Organization::firstOrFail();
        $this->assertSame(Organization::REVIEW_DRAFT, $community->review_status);
        $this->assertSame(Organization::OPERATIONAL_INACTIVE, $community->operational_status);
        $this->assertTrue($community->creator->is($youth));
        $this->assertSame(16, strlen($community->getKey()));
        $this->assertSame(16, strlen(DB::table('organizations')->value('category_id')));
    }

    public function test_youth_can_render_create_show_and_edit_own_draft(): void
    {
        $youth = $this->youth();
        $category = $this->category();
        $community = $this->community($youth, $category);

        $this->actingAs($youth)->get(route('youth.communities.index'))->assertOk()->assertSee('Pengajuan komunitas');
        $this->actingAs($youth)->get(route('youth.communities.create'))->assertOk()->assertSee('Buat draft komunitas');
        $this->actingAs($youth)->get(route('youth.communities.show', $community))->assertOk()->assertSee($community->name);
        $this->actingAs($youth)->get(route('youth.communities.edit', $community))->assertOk();

        $this->actingAs($youth)->put(route('youth.communities.update', $community), $this->validData($category, ['name' => 'Komunitas Pemuda Berkarya']))
            ->assertRedirect(route('youth.communities.show', $community));
        $this->assertSame('Komunitas Pemuda Berkarya', $community->fresh()->name);
    }

    public function test_youth_cannot_view_or_edit_another_users_draft(): void
    {
        $owner = $this->youth();
        $other = $this->youth();
        $category = $this->category();
        $community = $this->community($owner, $category);

        $this->actingAs($other)->get(route('youth.communities.show', $community))->assertForbidden();
        $this->actingAs($other)->get(route('youth.communities.edit', $community))->assertForbidden();
        $this->actingAs($other)->put(route('youth.communities.update', $community), $this->validData($category, ['name' => 'Diambil Alih']))->assertForbidden();
        $this->assertSame('Komunitas Pemuda', $community->fresh()->name);
    }

    public function test_submitting_a_draft_creates_pending_request_and_locks_editing(): void
    {
        $youth = $this->youth();
        $community = $this->community($youth, $this->category());

        $this->actingAs($youth)->post(route('youth.communities.submit', $community), ['submission_notes' => 'Mohon ditinjau.'])
            ->assertRedirect(route('youth.communities.show', $community));

        $this->assertSame(Organization::REVIEW_PENDING, $community->fresh()->review_status);
        $this->assertDatabaseHas('organization_verification_requests', [
            'organization_id' => $community->getKey(), 'submitted_by' => $youth->getKey(), 'status' => 'pending',
        ]);
        $this->actingAs($youth)->get(route('youth.communities.edit', $community))->assertForbidden();
        $this->actingAs($youth)->post(route('youth.communities.submit', $community))->assertForbidden();
    }

    public function test_invalid_community_input_is_rejected(): void
    {
        $youth = $this->youth();

        $this->actingAs($youth)->from(route('youth.communities.create'))->post(route('youth.communities.store'), [
            'category_id' => 'not-a-uuid',
            'name' => '',
            'contact_email' => 'invalid',
            'website_url' => 'javascript:alert(1)',
        ])->assertRedirect(route('youth.communities.create'))
            ->assertSessionHasErrors(['category_id', 'name', 'contact_email', 'website_url']);

        $this->assertDatabaseCount('organizations', 0);
    }

    public function test_guest_and_non_youth_are_blocked(): void
    {
        $this->get(route('youth.communities.index'))->assertRedirect(route('login'));

        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin)->get(route('youth.communities.index'))->assertForbidden();
        $this->actingAs($admin)->post(route('youth.communities.store'), [])->assertForbidden();
    }

    public function test_logo_is_stored_separately_and_only_served_to_owner(): void
    {
        Storage::fake('community_media');
        $owner = $this->youth();
        $other = $this->youth();
        $category = $this->category();

        $this->actingAs($owner)->post(route('youth.communities.store'), $this->validData($category, [
            'logo' => UploadedFile::fake()->image('logo.png', 300, 300),
        ]));

        $community = Organization::firstOrFail();
        Storage::disk('community_media')->assertExists($community->logo_path);
        $this->actingAs($owner)->get(route('youth.communities.logo', $community))->assertOk()->assertHeader('Cache-Control', 'no-store, private');
        $this->actingAs($other)->get(route('youth.communities.logo', $community))->assertForbidden();
        $this->actingAs($owner)->get(route('youth.communities.show', $community))->assertDontSee($community->logo_path);
    }

    public function test_only_verifier_can_see_pending_community_queue(): void
    {
        $owner = $this->youth();
        $community = $this->pendingCommunity($owner);
        $verifier = $this->verifier();

        $this->get(route('verifier.community-verifications.index'))->assertRedirect(route('login'));
        $this->actingAs($owner)->get(route('verifier.community-verifications.index'))->assertForbidden();

        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin)->get(route('verifier.community-verifications.index'))->assertForbidden();

        $this->actingAs($verifier)->get(route('verifier.community-verifications.index'))
            ->assertOk()->assertSee($community->name)->assertSee($owner->email);
        $this->actingAs($verifier)->get(route('verifier.community-verifications.show', $community))
            ->assertOk()->assertSee('Keputusan Verifier');
    }

    public function test_verifier_can_approve_and_activate_community_with_contextual_leader(): void
    {
        $owner = $this->youth();
        $community = $this->pendingCommunity($owner);
        $verifier = $this->verifier();

        $this->actingAs($verifier)->post(route('verifier.community-verifications.review', $community), [
            'decision' => 'approved',
        ])->assertRedirect(route('verifier.community-verifications.show', $community));

        $community->refresh();
        $this->assertSame(Organization::REVIEW_APPROVED, $community->review_status);
        $this->assertSame(Organization::OPERATIONAL_ACTIVE, $community->operational_status);
        $this->assertTrue($community->approvedBy?->is($verifier) ?? $community->approved_by === $verifier->getKey());
        $this->assertDatabaseHas('organization_memberships', [
            'organization_id' => $community->getKey(),
            'user_id' => $owner->getKey(),
            'access_role' => OrganizationMembership::ROLE_LEADER,
            'membership_status' => OrganizationMembership::STATUS_ACTIVE,
        ]);
        $this->assertFalse($owner->hasRole('leader'));
        $this->assertDatabaseHas('notifications', [
            'user_id' => $owner->getKey(), 'notification_type' => 'community_verification_result',
        ]);
    }

    public function test_revision_requires_note_and_youth_can_edit_and_resubmit(): void
    {
        $owner = $this->youth();
        $category = $this->category();
        $community = $this->pendingCommunity($owner, $category);
        $verifier = $this->verifier();

        $this->actingAs($verifier)->post(route('verifier.community-verifications.review', $community), [
            'decision' => 'revision',
        ])->assertSessionHasErrors('review_notes');

        $this->actingAs($verifier)->post(route('verifier.community-verifications.review', $community), [
            'decision' => 'revision', 'review_notes' => 'Lengkapi deskripsi tujuan komunitas.',
        ])->assertRedirect();
        $this->assertSame(Organization::REVIEW_REVISION, $community->fresh()->review_status);

        $this->actingAs($owner)->put(route('youth.communities.update', $community), $this->validData($category, [
            'description' => 'Deskripsi tujuan komunitas telah dilengkapi.',
        ]))->assertRedirect();
        $this->actingAs($owner)->post(route('youth.communities.submit', $community), [
            'submission_notes' => 'Perbaikan telah selesai.',
        ])->assertRedirect();

        $this->assertSame(Organization::REVIEW_PENDING, $community->fresh()->review_status);
        $this->assertSame(2, $community->verificationRequests()->count());
        $this->assertSame(1, $owner->siporaNotifications()->count());
    }

    public function test_rejection_requires_note_and_terminal_decision_cannot_be_replayed(): void
    {
        $owner = $this->youth();
        $community = $this->pendingCommunity($owner);
        $verifier = $this->verifier();

        $this->actingAs($verifier)->post(route('verifier.community-verifications.review', $community), [
            'decision' => 'rejected',
        ])->assertSessionHasErrors('review_notes');

        $this->actingAs($verifier)->post(route('verifier.community-verifications.review', $community), [
            'decision' => 'rejected', 'review_notes' => 'Data komunitas tidak dapat diverifikasi.',
        ])->assertRedirect();
        $this->assertSame(Organization::REVIEW_REJECTED, $community->fresh()->review_status);
        $this->assertSame(Organization::OPERATIONAL_INACTIVE, $community->fresh()->operational_status);

        $this->actingAs($verifier)->post(route('verifier.community-verifications.review', $community), [
            'decision' => 'approved',
        ])->assertSessionHasErrors('decision');
        $this->assertDatabaseCount('organization_memberships', 0);
        $this->assertSame(1, $owner->siporaNotifications()->count());
    }

    private function youth(): User
    {
        $user = User::factory()->create();
        $user->assignRole('youth');

        return $user;
    }

    private function verifier(): User
    {
        $user = User::factory()->create();
        $user->assignRole('verifier');

        return $user;
    }

    private function category(): OrganizationCategory
    {
        return OrganizationCategory::create([
            'name' => 'Kategori Uji',
            'slug' => 'kategori-uji',
            'description' => 'Hanya dibuat di database pengujian.',
        ]);
    }

    private function community(User $owner, OrganizationCategory $category): Organization
    {
        return Organization::create([
            'created_by_user_id' => $owner->getKey(),
            'category_id' => $category->getKey(),
            'name' => 'Komunitas Pemuda',
            'slug' => 'komunitas-pemuda-test',
            'description' => 'Wadah kolaborasi pemuda.',
            'social_links' => (object) [],
            'review_status' => Organization::REVIEW_DRAFT,
            'operational_status' => Organization::OPERATIONAL_INACTIVE,
        ]);
    }

    private function pendingCommunity(User $owner, ?OrganizationCategory $category = null): Organization
    {
        $community = $this->community($owner, $category ?? $this->category());
        $community->review_status = Organization::REVIEW_PENDING;
        $community->save();

        OrganizationVerificationRequest::create([
            'organization_id' => $community->getKey(),
            'submitted_by' => $owner->getKey(),
            'status' => OrganizationVerificationRequest::STATUS_PENDING,
            'submission_notes' => 'Mohon ditinjau.',
            'submitted_at' => now(),
        ]);

        return $community->fresh();
    }

    private function validData(OrganizationCategory $category, array $overrides = []): array
    {
        return array_merge([
            'category_id' => $category->uuid(),
            'name' => 'Komunitas Pemuda',
            'description' => 'Wadah kolaborasi pemuda Kabupaten Pemalang.',
            'contact_email' => 'komunitas@example.test',
            'contact_phone' => '+628123456789',
            'website_url' => 'https://example.test',
            'address_text' => 'Kabupaten Pemalang',
            'social_instagram' => 'https://instagram.com/komunitas',
        ], $overrides);
    }
}
