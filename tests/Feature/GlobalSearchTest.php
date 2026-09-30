<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\AdministrativeArea;
use App\Models\Opportunity;
use App\Models\OpportunityCategory;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlobalSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_groups_public_activity_and_community_and_excludes_internal_records(): void
    {
        [$public, $category, $creator, $area] = $this->context();
        $private = $this->organization('Digital Internal Review', 'digital-internal', $creator, $public->category, $area, 'pending_review', 'inactive');
        $this->activity('Digital Aman Publik', $public, $category, $creator, $area);
        $this->activity('Digital Draft Rahasia', $public, $category, $creator, $area, ['review_status' => 'draft', 'publication_status' => 'unpublished', 'published_at' => null]);
        $opportunityCategory = OpportunityCategory::create(['name' => 'Beasiswa', 'slug' => 'beasiswa']);
        Opportunity::create(['category_id' => $opportunityCategory->getKey(), 'title' => 'Beasiswa Digital Pemuda', 'slug' => 'beasiswa-digital-pemuda', 'provider_name' => 'Dindikpora', 'description' => 'Peluang digital.', 'administrative_area_id' => $area->getKey(), 'external_url' => 'https://example.test/beasiswa', 'deadline_at' => now()->addWeek(), 'publication_status' => Opportunity::STATUS_PUBLISHED, 'published_at' => now()->subMinute()]);

        $this->get(route('search.index', ['q' => 'Digital']))
            ->assertOk()
            ->assertSeeInOrder(['Activity', 'Digital Aman Publik', 'Community', $public->name, 'Opportunity', 'Beasiswa Digital Pemuda'])
            ->assertDontSee('Digital Draft Rahasia')
            ->assertDontSee($private->name);
    }

    public function test_blank_and_wildcard_search_are_handled_safely(): void
    {
        [$organization, $category, $creator, $area] = $this->context();
        $this->activity('Activity Publik', $organization, $category, $creator, $area);

        $this->get(route('search.index'))->assertOk()->assertSee('Mulai pencarianmu')->assertDontSee('Activity Publik');
        $this->get(route('search.index', ['q' => '%_\\']))->assertOk()->assertSee('Tidak ada Activity publik yang cocok.');
    }

    private function context(): array
    {
        $creator = User::factory()->create();
        $area = AdministrativeArea::create(['code' => '33.27.08', 'name' => 'Pemalang', 'area_level' => 'district']);
        $organizationCategory = OrganizationCategory::create(['name' => 'Teknologi Digital', 'slug' => 'komunitas-teknologi']);
        $organization = $this->organization('Komunitas Digital Publik', 'digital-publik', $creator, $organizationCategory, $area);
        $category = ActivityCategory::create(['name' => 'Teknologi Digital', 'slug' => 'teknologi']);

        return [$organization, $category, $creator, $area];
    }

    private function organization(string $name, string $slug, User $owner, OrganizationCategory $category, AdministrativeArea $area, string $review = 'approved', string $operational = 'active'): Organization
    {
        return Organization::create([
            'created_by_user_id' => $owner->getKey(), 'category_id' => $category->getKey(), 'administrative_area_id' => $area->getKey(),
            'name' => $name, 'slug' => $slug, 'description' => 'Deskripsi publik.', 'review_status' => $review,
            'operational_status' => $operational, 'approved_at' => $review === 'approved' ? now() : null,
        ]);
    }

    private function activity(string $title, Organization $organization, ActivityCategory $category, User $creator, AdministrativeArea $area, array $overrides = []): Activity
    {
        $start = now()->addWeek();

        return Activity::create(array_merge([
            'organization_id' => $organization->getKey(), 'category_id' => $category->getKey(), 'created_by_user_id' => $creator->getKey(),
            'title' => $title, 'slug' => str($title)->slug().'-'.str()->random(5), 'description' => 'Aman.', 'location_type' => 'online',
            'administrative_area_id' => $area->getKey(), 'start_at' => $start, 'end_at' => $start->copy()->addHours(2),
            'registration_mode' => 'open', 'review_status' => 'approved', 'publication_status' => 'published',
            'execution_status' => 'scheduled', 'published_at' => now(),
        ], $overrides));
    }
}
