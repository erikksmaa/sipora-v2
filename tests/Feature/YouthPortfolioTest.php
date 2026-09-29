<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\ActivityParticipation;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationExperience;
use App\Models\OrganizationMembership;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserCertificate;
use App\Models\UserEducation;
use App\Models\UserProfile;
use App\Models\UserProfileVisibility;
use App\Services\Youth\YouthPortfolioService;
use App\Support\BinaryUuid;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class YouthPortfolioTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_youth_can_view_composed_owner_portfolio_and_guest_cannot_open_workspace(): void
    {
        $owner = $this->youth('owner-portfolio', 'Pemilik Portfolio');
        $context = $this->verifiedContext($owner);
        $this->participation($context['activity'], $owner, 'accepted', 'completed');
        foreach ([['accepted', 'no_show'], ['accepted', 'pending'], ['rejected', 'pending'], ['cancelled', 'pending']] as $index => [$registration, $completion]) {
            $this->participation($this->activity($context['organization'], 'hidden-'.$index), $owner, $registration, $completion);
        }
        OrganizationMembership::create([
            'organization_id' => $context['organization']->getKey(), 'user_id' => $owner->getKey(),
            'access_role' => 'leader', 'position_title' => 'Ketua', 'membership_status' => 'active',
            'requested_at' => now(), 'approved_at' => now(), 'approved_by' => $owner->getKey(),
        ]);
        $inactive = $this->organization('inactive-membership');
        OrganizationMembership::create([
            'organization_id' => $inactive->getKey(), 'user_id' => $owner->getKey(),
            'access_role' => 'member', 'membership_status' => 'left', 'requested_at' => now(), 'ended_at' => now(),
        ]);

        $this->get(route('youth.portfolio.show'))->assertRedirectToRoute('login');
        $this->actingAs($owner)->get(route('youth.portfolio.show'))->assertOk()
            ->assertSee('Pemilik Portfolio')->assertSee($context['activity']->title)
            ->assertSee($context['organization']->name)->assertDontSee('Activity hidden-0')
            ->assertDontSee('Activity hidden-1')->assertDontSee('Activity hidden-2')->assertDontSee('Activity hidden-3')
            ->assertDontSee($inactive->name);
    }

    public function test_public_portfolio_respects_section_privacy_and_never_exposes_sensitive_data(): void
    {
        $owner = $this->youth('privacy-portfolio', 'Nama Publik', [
            'show_skills' => false, 'show_education' => false,
            'show_organization_experience' => false, 'show_achievements' => false,
        ], ['email' => 'private@example.test']);
        $owner->profile->update(['phone' => '081234567890', 'bio' => 'Bio aman untuk publik']);
        $skill = Skill::create(['name' => 'Keahlian Rahasia', 'slug' => 'keahlian-rahasia']);
        $owner->skills()->attach($skill->getKey(), ['id' => BinaryUuid::generate(), 'is_self_reported' => true]);
        UserEducation::create(['user_id' => $owner->getKey(), 'education_level' => 'S1', 'institution_name' => 'Kampus Rahasia']);
        OrganizationExperience::create(['user_id' => $owner->getKey(), 'organization_name' => 'Organisasi Rahasia']);
        UserAchievement::create(['user_id' => $owner->getKey(), 'title' => 'Prestasi Rahasia', 'verification_status' => 'self_reported']);

        $response = $this->get(route('portfolio.show', 'privacy-portfolio'))->assertOk()
            ->assertSee('Nama Publik')->assertSee('Bio aman untuk publik');
        foreach (['private@example.test', '081234567890', 'Keahlian Rahasia', 'Kampus Rahasia', 'Organisasi Rahasia', 'Prestasi Rahasia'] as $secret) {
            $response->assertDontSee($secret);
        }
        $response->assertDontSee('NIK')->assertDontSee('document_path')->assertDontSee('review_notes');

        $this->actingAs($owner)->get(route('youth.portfolio.show'))->assertOk()
            ->assertSee('Keahlian Rahasia')->assertSee('Kampus Rahasia')->assertSee('Organisasi Rahasia')
            ->assertSee('Prestasi Rahasia')->assertSee('Privat');
    }

    public function test_public_portfolio_distinguishes_verified_experience_and_self_reported_data(): void
    {
        $owner = $this->youth('trusted-portfolio', 'Pemuda Tepercaya');
        $skill = Skill::create(['name' => 'Public Speaking Mandiri', 'slug' => 'public-speaking-mandiri']);
        $owner->skills()->attach($skill->getKey(), ['id' => BinaryUuid::generate(), 'is_self_reported' => true]);
        UserAchievement::create(['user_id' => $owner->getKey(), 'title' => 'Prestasi Mandiri', 'verification_status' => 'self_reported']);
        $context = $this->verifiedContext($owner);
        $participation = $this->participation($context['activity'], $owner, 'accepted', 'completed');
        OrganizationMembership::create([
            'organization_id' => $context['organization']->getKey(), 'user_id' => $owner->getKey(),
            'access_role' => 'manager', 'position_title' => 'Koordinator', 'membership_status' => 'active',
            'requested_at' => now(), 'approved_at' => now(), 'approved_by' => $owner->getKey(),
        ]);
        $certificate = UserCertificate::create([
            'user_id' => $owner->getKey(), 'participation_id' => $participation->getKey(), 'source_type' => 'sipora',
            'name' => $context['activity']->title, 'issuer_name' => $context['organization']->name,
            'certificate_number' => 'SIPORA-PORTFOLIO-001', 'verification_code' => str_repeat('a', 64),
            'issued_at' => today(), 'verification_status' => 'verified',
        ]);

        $this->get(route('portfolio.show', 'trusted-portfolio'))->assertOk()
            ->assertSee('Pengalaman Terverifikasi SIPORA')->assertSee('Public Speaking Mandiri')
            ->assertSee('Prestasi Mandiri')->assertSee('Data mandiri')->assertSee('Koordinator')
            ->assertSee(route('certificates.verify', $certificate->verification_code), false)
            ->assertDontSee(route('youth.certificates.download', $certificate), false)
            ->assertDontSee('Keahlian Terverifikasi');
    }

    public function test_private_portfolio_and_hidden_photo_are_not_publicly_accessible(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('profile-photos/portfolio.jpg', 'photo-bytes');
        $private = $this->youth('private-portfolio', 'Profil Privat', ['is_profile_public' => false]);
        $private->profile->update(['profile_photo_path' => 'profile-photos/portfolio.jpg']);
        $hiddenPhoto = $this->youth('hidden-photo', 'Foto Privat', ['show_photo' => false]);
        $hiddenPhoto->profile->update(['profile_photo_path' => 'profile-photos/portfolio.jpg']);
        $publicPhoto = $this->youth('public-photo', 'Foto Publik');
        $publicPhoto->profile->update(['profile_photo_path' => 'profile-photos/portfolio.jpg']);

        $this->get(route('portfolio.show', 'private-portfolio'))->assertNotFound();
        $this->get(route('portfolio.photo', 'private-portfolio'))->assertNotFound();
        $this->get(route('portfolio.photo', 'hidden-photo'))->assertNotFound();
        $this->get(route('portfolio.photo', 'public-photo'))->assertOk()->assertHeader('x-content-type-options', 'nosniff');
    }

    public function test_portfolio_query_count_remains_bounded_as_cards_grow(): void
    {
        $owner = $this->youth('query-portfolio', 'Query Portfolio');
        $context = $this->verifiedContext($owner);
        foreach (range(1, 3) as $index) {
            $this->participation($this->activity($context['organization'], 'query-'.$index), $owner, 'accepted', 'completed');
        }

        $queries = 0;
        DB::listen(function () use (&$queries): void {
            $queries++;
        });
        $portfolio = app(YouthPortfolioService::class)->forPublicSlug('query-portfolio');

        $this->assertNotNull($portfolio);
        $this->assertCount(3, $portfolio['activities']);
        $this->assertLessThanOrEqual(22, $queries);
    }

    private function youth(string $slug, string $name, array $visibility = [], array $attributes = []): User
    {
        $user = User::factory()->create($attributes + ['name' => $name]);
        $user->assignRole('youth');
        UserProfile::create([
            'user_id' => $user->getKey(), 'public_slug' => $slug, 'full_name' => $name,
            'birth_date' => '2002-01-01', 'bio' => 'Profil pengembangan SIPORA.', 'occupation_title' => 'Pemuda Pemalang',
        ]);
        UserProfileVisibility::create($visibility + [
            'user_id' => $user->getKey(), 'is_profile_public' => true, 'show_photo' => true,
            'show_bio' => true, 'show_interests' => true, 'show_skills' => true, 'show_education' => true,
            'show_organization_experience' => true, 'show_community_membership' => true,
            'show_activity_passport' => true, 'show_certificates' => true, 'show_achievements' => true,
            'show_business_experience' => true,
        ]);

        return $user->fresh(['profile', 'profileVisibility']);
    }

    private function verifiedContext(User $creator): array
    {
        $organization = $this->organization('verified-community', $creator);

        return ['organization' => $organization, 'activity' => $this->activity($organization, 'verified')];
    }

    private function organization(string $slug, ?User $creator = null): Organization
    {
        $creator ??= User::factory()->create();
        $category = OrganizationCategory::firstOrCreate(['slug' => 'portfolio-community'], ['name' => 'Kategori Portfolio']);

        return Organization::create([
            'created_by_user_id' => $creator->getKey(), 'category_id' => $category->getKey(),
            'name' => 'Community '.$slug, 'slug' => $slug, 'description' => 'Community Portfolio',
            'social_links' => (object) [], 'review_status' => Organization::REVIEW_APPROVED,
            'operational_status' => Organization::OPERATIONAL_ACTIVE, 'approved_at' => now(),
        ]);
    }

    private function activity(Organization $organization, string $slug): Activity
    {
        $category = ActivityCategory::firstOrCreate(['slug' => 'portfolio-activity'], ['name' => 'Kategori Activity']);

        return Activity::create([
            'organization_id' => $organization->getKey(), 'category_id' => $category->getKey(),
            'created_by_user_id' => $organization->created_by_user_id, 'title' => 'Activity '.$slug, 'slug' => 'activity-'.$slug,
            'description' => 'Activity Portfolio', 'location_type' => 'offline', 'venue_name' => 'Pemalang',
            'start_at' => now()->subDays(3), 'end_at' => now()->subDays(2), 'registration_mode' => 'open',
            'review_status' => Activity::REVIEW_APPROVED, 'publication_status' => Activity::PUBLICATION_PUBLISHED,
            'execution_status' => Activity::EXECUTION_COMPLETED, 'published_at' => now()->subWeek(),
        ]);
    }

    private function participation(Activity $activity, User $user, string $registration, string $completion): ActivityParticipation
    {
        return ActivityParticipation::create([
            'activity_id' => $activity->getKey(), 'user_id' => $user->getKey(), 'activity_role' => 'participant',
            'registration_status' => $registration, 'completion_status' => $completion,
            'completed_at' => $completion === 'completed' ? now() : null, 'requested_at' => now()->subWeek(),
        ]);
    }
}
