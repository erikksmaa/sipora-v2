<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\AdministrativeArea;
use App\Models\Interest;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationMembership;
use App\Models\User;
use App\Models\UserInterest;
use App\Services\Discovery\YouthDiscoveryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_allowed_interest_context_drives_explainable_activity_and_community_suggestions(): void
    {
        [$youth, $creator, $area, $technologyCommunity, $sportsCommunity] = $this->context();
        $technology = ActivityCategory::create(['name' => 'Teknologi', 'slug' => 'teknologi']);
        $sports = ActivityCategory::create(['name' => 'Olahraga', 'slug' => 'olahraga']);
        $interest = Interest::create(['name' => 'Teknologi', 'slug' => 'teknologi']);
        UserInterest::create(['user_id' => $youth->getKey(), 'interest_id' => $interest->getKey()]);
        $matching = $this->activity('Workshop Teknologi', $technologyCommunity, $technology, $creator, $area);
        $this->activity('Latihan Olahraga', $sportsCommunity, $sports, $creator, $area);
        $this->activity('Teknologi Tidak Publik', $technologyCommunity, $technology, $creator, $area, ['publication_status' => 'unpublished', 'published_at' => null]);

        $result = app(YouthDiscoveryService::class)->for($youth);

        $this->assertSame('personalized', $result['activity_mode']);
        $this->assertTrue($result['activities']->contains($matching));
        $this->assertFalse($result['activities']->contains('title', 'Latihan Olahraga'));
        $this->assertFalse($result['activities']->contains('title', 'Teknologi Tidak Publik'));
        $this->assertSame('Karena kamu tertarik pada Teknologi', $result['activities']->first()->discovery_reason);
        $this->assertTrue($result['communities']->contains($technologyCommunity));
    }

    public function test_joined_community_is_excluded_and_youth_without_interests_gets_neutral_fallback(): void
    {
        [$youth, $creator, $area, $joined, $available] = $this->context();
        $category = ActivityCategory::create(['name' => 'Teknologi', 'slug' => 'teknologi']);
        $this->activity('Activity Netral', $available, $category, $creator, $area);
        OrganizationMembership::create([
            'organization_id' => $joined->getKey(), 'user_id' => $youth->getKey(), 'access_role' => 'member',
            'membership_status' => 'active', 'requested_at' => now()->subDay(), 'approved_at' => now(), 'approved_by' => $creator->getKey(),
        ]);

        $result = app(YouthDiscoveryService::class)->for($youth);

        $this->assertSame('fallback', $result['activity_mode']);
        $this->assertTrue($result['activities']->contains('title', 'Activity Netral'));
        $this->assertFalse($result['communities']->contains($joined));
        $this->assertTrue($result['communities']->contains($available));
        $this->assertFalse($result['activities']->first()->relationLoaded('participations'));
    }

    private function context(): array
    {
        $youth = User::factory()->create();
        $creator = User::factory()->create();
        $area = AdministrativeArea::create(['code' => '33.27.08', 'name' => 'Pemalang', 'area_level' => 'district']);
        $technologyCategory = OrganizationCategory::create(['name' => 'Komunitas Teknologi', 'slug' => 'komunitas-teknologi']);
        $sportsCategory = OrganizationCategory::create(['name' => 'Komunitas Olahraga', 'slug' => 'komunitas-olahraga']);

        return [
            $youth, $creator, $area,
            $this->community('Komunitas Teknologi', 'komunitas-teknologi', $technologyCategory, $creator, $area),
            $this->community('Komunitas Olahraga', 'komunitas-olahraga', $sportsCategory, $creator, $area),
        ];
    }

    private function community(string $name, string $slug, OrganizationCategory $category, User $creator, AdministrativeArea $area): Organization
    {
        return Organization::create([
            'created_by_user_id' => $creator->getKey(), 'category_id' => $category->getKey(), 'administrative_area_id' => $area->getKey(),
            'name' => $name, 'slug' => $slug, 'review_status' => 'approved', 'operational_status' => 'active', 'approved_at' => now(),
        ]);
    }

    private function activity(string $title, Organization $organization, ActivityCategory $category, User $creator, AdministrativeArea $area, array $overrides = []): Activity
    {
        $start = now()->addWeek();

        return Activity::create(array_merge([
            'organization_id' => $organization->getKey(), 'category_id' => $category->getKey(), 'created_by_user_id' => $creator->getKey(),
            'title' => $title, 'slug' => str($title)->slug().'-'.str()->random(5), 'location_type' => 'offline',
            'administrative_area_id' => $area->getKey(), 'start_at' => $start, 'end_at' => $start->copy()->addHours(2),
            'registration_mode' => 'open', 'review_status' => 'approved', 'publication_status' => 'published',
            'execution_status' => 'scheduled', 'published_at' => now(),
        ], $overrides));
    }
}
