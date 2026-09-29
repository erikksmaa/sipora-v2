<?php

namespace Tests\Feature;

use App\Models\AdministrativeArea;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommunityDiscoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_approved_active_communities_are_discoverable(): void
    {
        [$category, $area, $owner] = $this->context();
        $this->community('Komunitas Publik', 'publik', $category, $area, $owner);
        $this->community('Komunitas Pending Rahasia', 'pending', $category, $area, $owner, ['review_status' => 'pending_review', 'operational_status' => 'inactive', 'approved_at' => null]);
        $this->community('Komunitas Ditolak Rahasia', 'ditolak', $category, $area, $owner, ['review_status' => 'rejected', 'operational_status' => 'inactive', 'approved_at' => null]);
        $this->community('Komunitas Suspended Rahasia', 'suspended', $category, $area, $owner, ['operational_status' => 'suspended']);
        $this->community('Komunitas Archived Rahasia', 'archived', $category, $area, $owner, ['operational_status' => 'archived']);

        $this->get(route('communities.index'))->assertOk()
            ->assertSee('Komunitas Publik')
            ->assertDontSee('Pending Rahasia')
            ->assertDontSee('Ditolak Rahasia')
            ->assertDontSee('Suspended Rahasia')
            ->assertDontSee('Archived Rahasia');
    }

    public function test_search_category_and_location_filters_use_public_fields(): void
    {
        [$category, $area, $owner] = $this->context();
        $otherCategory = OrganizationCategory::create(['name' => 'Olahraga', 'slug' => 'komunitas-olahraga']);
        $otherArea = AdministrativeArea::create(['code' => '33.27.09', 'name' => 'Taman', 'area_level' => 'district']);
        $this->community('Komunitas Programmer Pemalang', 'programmer', $category, $area, $owner, ['description' => 'Belajar teknologi web bersama.']);
        $this->community('Klub Lari Taman', 'lari', $otherCategory, $otherArea, $owner);

        $this->get(route('communities.index', ['q' => 'teknologi']))->assertOk()->assertSee('Komunitas Programmer')->assertDontSee('Klub Lari');
        $this->get(route('communities.index', ['category' => $otherCategory->slug, 'location' => $otherArea->code]))->assertOk()->assertSee('Klub Lari')->assertDontSee('Komunitas Programmer');
    }

    private function context(): array
    {
        return [
            OrganizationCategory::create(['name' => 'Teknologi', 'slug' => 'komunitas-teknologi']),
            AdministrativeArea::create(['code' => '33.27.08', 'name' => 'Pemalang', 'area_level' => 'district']),
            User::factory()->create(),
        ];
    }

    private function community(string $name, string $slug, OrganizationCategory $category, AdministrativeArea $area, User $owner, array $overrides = []): Organization
    {
        return Organization::create(array_merge([
            'created_by_user_id' => $owner->getKey(), 'category_id' => $category->getKey(), 'administrative_area_id' => $area->getKey(),
            'name' => $name, 'slug' => $slug, 'description' => 'Community aktif di Pemalang.',
            'review_status' => 'approved', 'operational_status' => 'active', 'approved_at' => now(),
        ], $overrides));
    }
}
