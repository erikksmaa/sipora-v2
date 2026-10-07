<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\ActivityParticipation;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationMembership;
use App\Models\User;
use App\Models\UserCertificate;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LogicException;
use Tests\TestCase;

class CertificateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_completed_accepted_participant_can_receive_one_verified_certificate(): void
    {
        [$community, $manager, $activity] = $this->context('eligible');
        $youth = $this->youth(['name' => 'Pemuda Penerima']);
        $participation = $this->participation($activity, $youth);
        $route = $this->issueRoute($community, $activity, $participation);

        $this->actingAs($manager)->post($route)->assertRedirect();
        $certificate = UserCertificate::firstOrFail();

        $this->assertSame(16, strlen($certificate->getKey()));
        $this->assertSame(UserCertificate::SOURCE_SIPORA, $certificate->source_type);
        $this->assertSame(UserCertificate::STATUS_VERIFIED, $certificate->verification_status);
        $this->assertSame($activity->title, $certificate->name);
        $this->assertSame(64, strlen($certificate->verification_code));
        $this->assertDatabaseHas('activity_log', ['event' => 'certificate_issued']);
        $this->assertSame('activity_certificate_issued', $youth->siporaNotifications()->firstOrFail()->notification_type);

        $this->actingAs($manager)->post($route)->assertSessionHasErrors('certificate');
        $this->assertDatabaseCount('user_certificates', 1);
    }

    public function test_ineligible_participations_and_disabled_activity_cannot_receive_certificate(): void
    {
        $cases = [
            ['pending-registration', ActivityParticipation::REGISTRATION_PENDING, ActivityParticipation::COMPLETION_PENDING, true],
            ['rejected', ActivityParticipation::REGISTRATION_REJECTED, ActivityParticipation::COMPLETION_COMPLETED, true],
            ['cancelled', ActivityParticipation::REGISTRATION_CANCELLED, ActivityParticipation::COMPLETION_COMPLETED, true],
            ['pending-completion', ActivityParticipation::REGISTRATION_ACCEPTED, ActivityParticipation::COMPLETION_PENDING, true],
            ['no-show', ActivityParticipation::REGISTRATION_ACCEPTED, ActivityParticipation::COMPLETION_NO_SHOW, true],
            ['disabled', ActivityParticipation::REGISTRATION_ACCEPTED, ActivityParticipation::COMPLETION_COMPLETED, false],
        ];

        foreach ($cases as [$slug, $registration, $completion, $enabled]) {
            [$community, $manager, $activity] = $this->context($slug, $enabled);
            $participation = $this->participation($activity, $this->youth(), $registration, $completion);
            $this->actingAs($manager)->post($this->issueRoute($community, $activity, $participation))
                ->assertSessionHasErrors('certificate');
        }

        $this->assertDatabaseCount('user_certificates', 0);
    }

    public function test_only_contextual_manager_or_leader_can_issue_for_own_community(): void
    {
        [$community, $manager, $activity] = $this->context('authorization', true, OrganizationMembership::ROLE_MANAGER);
        [$otherCommunity, $otherManager] = $this->context('other');
        $participant = $this->youth();
        $member = $this->youth();
        OrganizationMembership::create(['organization_id' => $community->getKey(), 'user_id' => $member->getKey(), 'access_role' => OrganizationMembership::ROLE_MEMBER,
            'membership_status' => OrganizationMembership::STATUS_ACTIVE, 'requested_at' => now(), 'approved_at' => now(), 'approved_by' => $manager->getKey()]);
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $verifier = User::factory()->create();
        $verifier->assignRole('verifier');
        $participation = $this->participation($activity, $participant);
        $route = $this->issueRoute($community, $activity, $participation);

        $this->post($route)->assertRedirectToRoute('login');
        $this->actingAs($otherManager)->post($route)->assertForbidden();
        $this->actingAs($member)->post($route)->assertForbidden();
        $this->actingAs($participant)->post($route)->assertForbidden();
        $this->actingAs($admin)->post($route)->assertForbidden();
        $this->actingAs($verifier)->post($route)->assertForbidden();
        $this->actingAs($manager)->post(route('manager.activities.participants.certificate', [$otherCommunity, $activity, $participation]))->assertNotFound();
        $this->actingAs($manager)->post($route)->assertRedirect();
        $this->assertDatabaseCount('user_certificates', 1);
    }

    public function test_verification_code_has_database_uniqueness(): void
    {
        [$community, $manager, $activity] = $this->context('unique-one');
        $first = $this->participation($activity, $this->youth());
        $this->actingAs($manager)->post($this->issueRoute($community, $activity, $first));
        $issued = UserCertificate::firstOrFail();

        [$secondCommunity, $secondManager, $secondActivity] = $this->context('unique-two');
        $second = $this->participation($secondActivity, $this->youth());
        $this->actingAs($secondManager)->post($this->issueRoute($secondCommunity, $secondActivity, $second))->assertRedirect();
        $secondIssued = UserCertificate::query()->where('participation_id', $second->getKey())->firstOrFail();
        $this->assertNotSame($issued->certificate_number, $secondIssued->certificate_number);
        $this->assertNotSame($issued->verification_code, $secondIssued->verification_code);

        [$thirdCommunity, $thirdManager, $thirdActivity] = $this->context('unique-three');
        $third = $this->participation($thirdActivity, $this->youth());

        $this->expectException(QueryException::class);
        UserCertificate::create([
            'user_id' => $third->user_id,
            'participation_id' => $third->getKey(),
            'source_type' => UserCertificate::SOURCE_SIPORA,
            'name' => $thirdActivity->title,
            'issuer_name' => $thirdActivity->organization->name,
            'certificate_number' => 'SIPORA-UNIQUE-SECOND',
            'verification_code' => $issued->verification_code,
            'issued_at' => today(),
            'verification_status' => UserCertificate::STATUS_VERIFIED,
        ]);
    }

    public function test_owner_can_view_and_download_pdf_but_other_youth_cannot(): void
    {
        [$community, $manager, $activity] = $this->context('private');
        $owner = $this->youth(['name' => 'Pemilik Sertifikat']);
        $other = $this->youth();
        $participation = $this->participation($activity, $owner);
        $this->actingAs($manager)->post($this->issueRoute($community, $activity, $participation));
        $certificate = UserCertificate::firstOrFail();

        $this->actingAs($owner)->get(route('youth.certificates.index'))->assertOk()->assertSee($activity->title);
        $this->actingAs($owner)->get(route('youth.certificates.show', $certificate))->assertOk()->assertSee('Pemilik Sertifikat');
        $this->actingAs($owner)->get(route('youth.certificates.download', $certificate))
            ->assertOk()->assertHeader('content-type', 'application/pdf')->assertSee('%PDF-1.4', false);
        $this->actingAs($other)->get(route('youth.certificates.show', $certificate))->assertNotFound();
        $this->actingAs($other)->get(route('youth.certificates.download', $certificate))->assertNotFound();
    }

    public function test_sipora_certificate_number_and_verification_code_are_immutable(): void
    {
        [$community, $manager, $activity] = $this->context('immutable');
        $participation = $this->participation($activity, $this->youth());
        $this->actingAs($manager)->post($this->issueRoute($community, $activity, $participation));

        $this->expectException(LogicException::class);
        UserCertificate::firstOrFail()->update(['verification_code' => str_repeat('a', 64)]);
    }

    public function test_public_verification_uses_database_record_and_exposes_only_safe_data(): void
    {
        [$community, $manager, $activity] = $this->context('public');
        $youth = $this->youth(['name' => 'Nama Publik Aman', 'email' => 'rahasia@example.test']);
        $participation = $this->participation($activity, $youth);
        $this->actingAs($manager)->post($this->issueRoute($community, $activity, $participation));
        $certificate = UserCertificate::firstOrFail();

        $this->get(route('certificates.verify', $certificate->verification_code))
            ->assertOk()->assertSee('Sertifikat valid')->assertSee('Nama Publik Aman')->assertSee($activity->title)
            ->assertDontSee('rahasia@example.test');
        $this->get(route('certificates.verify', str_repeat('0', 64)))
            ->assertOk()->assertSee('Sertifikat tidak valid')->assertDontSee('Sertifikat valid');
    }

    public function test_passport_shows_certificate_only_when_present(): void
    {
        [$community, $manager, $activity] = $this->context('passport-certified');
        $youth = $this->youth();
        $participation = $this->participation($activity, $youth);

        $this->actingAs($youth)->get(route('youth.passport.index'))->assertOk()->assertDontSee('Sertifikat tersedia');
        $this->actingAs($manager)->post($this->issueRoute($community, $activity, $participation));
        $this->actingAs($youth)->get(route('youth.passport.index'))->assertOk()->assertSee('Sertifikat tersedia');
        $this->actingAs($youth)->get(route('youth.passport.show', $participation))->assertOk()->assertSee('Lihat sertifikat');
    }

    private function issueRoute(Organization $community, Activity $activity, ActivityParticipation $participation): string
    {
        return route('manager.activities.participants.certificate', [$community, $activity, $participation]);
    }

    private function context(string $slug, bool $certificateEnabled = true, string $managerRole = OrganizationMembership::ROLE_LEADER): array
    {
        $manager = $this->youth();
        $organizationCategory = OrganizationCategory::create(['name' => 'Kategori '.$slug, 'slug' => 'organization-'.$slug]);
        $community = Organization::create(['created_by_user_id' => $manager->getKey(), 'category_id' => $organizationCategory->getKey(), 'name' => 'Komunitas '.$slug,
            'slug' => 'community-'.$slug, 'description' => 'Komunitas aktif', 'social_links' => (object) [], 'review_status' => Organization::REVIEW_APPROVED,
            'operational_status' => Organization::OPERATIONAL_ACTIVE, 'approved_at' => now()]);
        OrganizationMembership::create(['organization_id' => $community->getKey(), 'user_id' => $manager->getKey(), 'access_role' => $managerRole,
            'membership_status' => OrganizationMembership::STATUS_ACTIVE, 'requested_at' => now(), 'approved_at' => now(), 'approved_by' => $manager->getKey()]);
        $category = ActivityCategory::firstOrCreate(['slug' => 'certificate-category'], ['name' => 'Kategori Sertifikat']);
        $activity = Activity::create(['organization_id' => $community->getKey(), 'category_id' => $category->getKey(), 'created_by_user_id' => $manager->getKey(),
            'title' => 'Activity '.$slug, 'slug' => 'activity-'.$slug, 'description' => 'Activity sertifikat.', 'location_type' => 'offline', 'venue_name' => 'Gedung Pemuda',
            'start_at' => now()->subDays(2), 'end_at' => now()->subDay(), 'registration_mode' => 'open', 'certificate_enabled' => $certificateEnabled,
            'review_status' => Activity::REVIEW_APPROVED, 'publication_status' => Activity::PUBLICATION_PUBLISHED,
            'execution_status' => Activity::EXECUTION_COMPLETED, 'published_at' => now()->subWeek()]);

        return [$community, $manager, $activity];
    }

    private function participation(Activity $activity, User $user, string $registration = ActivityParticipation::REGISTRATION_ACCEPTED, string $completion = ActivityParticipation::COMPLETION_COMPLETED): ActivityParticipation
    {
        return ActivityParticipation::create(['activity_id' => $activity->getKey(), 'user_id' => $user->getKey(), 'activity_role' => 'participant',
            'registration_status' => $registration, 'completion_status' => $completion,
            'completed_at' => $completion === ActivityParticipation::COMPLETION_COMPLETED ? now() : null, 'requested_at' => now()]);
    }

    private function youth(array $attributes = []): User
    {
        $user = User::factory()->create($attributes);
        $user->assignRole('youth');

        return $this->completeYouthOnboarding($user);
    }
}
