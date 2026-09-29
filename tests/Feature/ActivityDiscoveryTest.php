<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\AdministrativeArea;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityDiscoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_approved_published_upcoming_activities_are_discoverable_and_sorted(): void
    {
        [$organization, $category, $creator, $area] = $this->context();
        $later = $this->activity('Kelas Publik Kedua', $organization, $category, $creator, $area, now()->addDays(8));
        $sooner = $this->activity('Kelas Publik Pertama', $organization, $category, $creator, $area, now()->addDays(2));
        $this->activity('Activity Belum Terbit', $organization, $category, $creator, $area, now()->addDay(), ['publication_status' => 'unpublished', 'published_at' => null]);
        $this->activity('Activity Masih Ditinjau', $organization, $category, $creator, $area, now()->addDay(), ['review_status' => 'pending_review', 'publication_status' => 'unpublished', 'published_at' => null]);

        $this->get(route('activities.index'))
            ->assertOk()
            ->assertSeeInOrder([$sooner->title, $later->title])
            ->assertDontSee('Activity Belum Terbit')
            ->assertDontSee('Activity Masih Ditinjau');
    }

    public function test_search_category_location_and_pagination_query_are_preserved(): void
    {
        [$organization, $category, $creator, $area] = $this->context();
        $otherCategory = ActivityCategory::create(['name' => 'Olahraga', 'slug' => 'olahraga']);
        $this->activity('Workshop Laravel Pemuda', $organization, $category, $creator, $area, now()->addDays(2));
        $this->activity('Latihan Futsal', $organization, $otherCategory, $creator, $area, now()->addDays(3));

        for ($index = 1; $index <= 12; $index++) {
            $this->activity('Teknologi Tambahan '.$index, $organization, $category, $creator, $area, now()->addDays(3 + $index));
        }

        $response = $this->get(route('activities.index', [
            'q' => 'Teknologi', 'category' => 'teknologi', 'location' => $area->code,
        ]));

        $response->assertOk()->assertSee('Teknologi Tambahan 1')->assertDontSee('Latihan Futsal');
        $this->assertStringContainsString('category=teknologi', $response->getContent());
        $this->assertStringContainsString('location='.$area->code, $response->getContent());
        $this->assertStringContainsString('page=2', $response->getContent());

        $this->get(route('activities.index', ['q' => 'Laravel']))->assertOk()->assertSee('Workshop Laravel Pemuda')->assertDontSee('Latihan Futsal');
    }

    private function context(): array
    {
        $creator = User::factory()->create();
        $area = AdministrativeArea::create(['code' => '33.27.08', 'name' => 'Pemalang', 'area_level' => 'district']);
        $organizationCategory = OrganizationCategory::create(['name' => 'Teknologi', 'slug' => 'komunitas-teknologi']);
        $organization = Organization::create([
            'created_by_user_id' => $creator->getKey(), 'category_id' => $organizationCategory->getKey(),
            'administrative_area_id' => $area->getKey(), 'name' => 'Komunitas Digital Pemalang', 'slug' => 'komunitas-digital-pemalang',
            'description' => 'Komunitas publik.', 'review_status' => 'approved', 'operational_status' => 'active', 'approved_at' => now(),
        ]);
        $category = ActivityCategory::create(['name' => 'Teknologi', 'slug' => 'teknologi']);

        return [$organization, $category, $creator, $area];
    }

    private function activity(string $title, Organization $organization, ActivityCategory $category, User $creator, AdministrativeArea $area, $start, array $overrides = []): Activity
    {
        return Activity::create(array_merge([
            'organization_id' => $organization->getKey(), 'category_id' => $category->getKey(), 'created_by_user_id' => $creator->getKey(),
            'title' => $title, 'slug' => str($title)->slug().'-'.str()->random(6), 'description' => 'Deskripsi aman untuk publik.',
            'location_type' => 'offline', 'venue_name' => 'Gedung Pemuda', 'administrative_area_id' => $area->getKey(),
            'start_at' => $start, 'end_at' => $start->copy()->addHours(3), 'registration_mode' => 'open',
            'review_status' => 'approved', 'publication_status' => 'published', 'execution_status' => 'scheduled', 'published_at' => now(),
        ], $overrides));
    }
}
