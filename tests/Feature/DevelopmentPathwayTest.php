<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\AdministrativeArea;
use App\Models\Interest;
use App\Models\Opportunity;
use App\Models\OpportunityCategory;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\Skill;
use App\Models\User;
use App\Services\Youth\DevelopmentPathwayService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DevelopmentPathwayTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_pathway_is_private_and_guides_youth_with_insufficient_taxonomy(): void
    {
        $youth = User::factory()->create()->assignRole('youth');
        $this->completeYouthOnboarding($youth, 'profile');

        $this->get(route('youth.development-pathway.index'))->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create()->assignRole('admin'))
            ->get(route('youth.development-pathway.index'))->assertForbidden();
        $this->actingAs($youth)->get(route('youth.development-pathway.index'))
            ->assertOk()->assertSee('Lengkapi minat dan keahlianmu')
            ->assertSee('Kelola Minat')->assertSee('Kelola Keahlian');
    }

    public function test_recommendations_use_real_taxonomy_and_exclude_unavailable_or_registered_items(): void
    {
        $youth = User::factory()->create()->assignRole('youth');
        $interest = Interest::create(['name' => 'Teknologi', 'slug' => 'teknologi']);
        $skill = Skill::create(['name' => 'Pemrograman Web', 'slug' => 'pemrograman-web']);
        $this->completeYouthOnboarding($youth);
        $area = AdministrativeArea::query()->firstOrFail();
        $category = ActivityCategory::create(['name' => 'Teknologi', 'slug' => 'teknologi']);
        $organizationCategory = OrganizationCategory::create(['name' => 'Komunitas Teknologi', 'slug' => 'komunitas-teknologi']);
        $organization = Organization::create([
            'created_by_user_id' => $youth->getKey(), 'category_id' => $organizationCategory->getKey(),
            'administrative_area_id' => $area->getKey(), 'name' => 'Komunitas Web', 'slug' => 'komunitas-web',
            'description' => 'Belajar pemrograman web.', 'review_status' => 'approved', 'operational_status' => 'active', 'approved_at' => now(),
        ]);
        $available = $this->activity('Workshop Web Terbuka', $organization, $category, $youth, $area);
        $registered = $this->activity('Workshop Web Sudah Diikuti', $organization, $category, $youth, $area);
        $registered->participations()->create(['user_id' => $youth->getKey(), 'registration_status' => 'accepted', 'completion_status' => 'pending', 'requested_at' => now()]);
        $this->activity('Workshop Web Kadaluarsa', $organization, $category, $youth, $area, ['registration_close_at' => now()->subDay()]);
        $full = $this->activity('Workshop Web Penuh', $organization, $category, $youth, $area, ['quota' => 1]);
        $full->participations()->create(['user_id' => User::factory()->create()->getKey(), 'registration_status' => 'accepted', 'completion_status' => 'pending', 'requested_at' => now()]);
        $this->activity('Workshop Web Rahasia', $organization, $category, $youth, $area, ['publication_status' => 'unpublished']);
        $completed = $this->activity('Workshop Web Selesai', $organization, $category, $youth, $area, [
            'execution_status' => 'completed', 'start_at' => now()->subWeeks(2), 'end_at' => now()->subWeeks(2)->addHours(2),
        ]);
        $completed->participations()->create(['user_id' => $youth->getKey(), 'registration_status' => 'accepted', 'completion_status' => 'completed', 'completed_at' => now()->subWeek(), 'requested_at' => now()->subWeeks(3)]);

        $opportunityCategory = OpportunityCategory::create(['name' => 'Magang', 'slug' => 'magang']);
        $this->opportunity('Magang Web Terbuka', $opportunityCategory, $youth);
        $this->opportunity('Beasiswa Umum', $opportunityCategory, $youth, ['description' => 'Data contoh pengembangan pemuda.']);
        $this->opportunity('Magang Web Kadaluarsa', $opportunityCategory, $youth, ['deadline_at' => now()->subDay()]);
        $this->opportunity('Magang Web Draft', $opportunityCategory, $youth, ['publication_status' => 'draft']);

        $data = app(DevelopmentPathwayService::class)->for($youth);
        $this->assertTrue($data['ready']);
        $this->assertSame($interest->name, $data['interests'][0]);
        $this->assertSame($skill->name, $data['skills'][0]);
        $this->assertSame(1, $data['completedCount']);
        $this->assertCount(1, $data['recommendations']['activity']);
        $this->assertSame($available->title, $data['recommendations']['activity'][0]['item']->title);
        $this->assertCount(1, $data['recommendations']['community']);
        $this->assertCount(1, $data['recommendations']['opportunity']);
        $this->assertContains('Kategori sesuai minat Teknologi', $data['recommendations']['activity'][0]['reasons']);

        $this->actingAs($youth)->get(route('youth.development-pathway.index'))
            ->assertOk()->assertSee('Workshop Web Terbuka')->assertSee('Magang Web Terbuka')
            ->assertSee('Workshop Web Selesai')->assertDontSee('Workshop Web Kadaluarsa')
            ->assertDontSee('Magang Web Kadaluarsa')->assertDontSee('Beasiswa Umum');
    }

    private function activity(string $title, Organization $organization, ActivityCategory $category, User $creator, AdministrativeArea $area, array $overrides = []): Activity
    {
        return Activity::create(array_merge([
            'organization_id' => $organization->getKey(), 'category_id' => $category->getKey(), 'created_by_user_id' => $creator->getKey(),
            'title' => $title, 'slug' => str($title)->slug(), 'description' => 'Belajar pemrograman web.',
            'location_type' => 'offline', 'venue_name' => 'Pemalang', 'administrative_area_id' => $area->getKey(),
            'start_at' => now()->addWeek(), 'end_at' => now()->addWeek()->addHours(2), 'registration_mode' => 'open',
            'review_status' => 'approved', 'publication_status' => 'published', 'execution_status' => 'scheduled', 'published_at' => now(),
        ], $overrides));
    }

    private function opportunity(string $title, OpportunityCategory $category, User $creator, array $overrides = []): Opportunity
    {
        return Opportunity::create(array_merge([
            'category_id' => $category->getKey(), 'created_by_user_id' => $creator->getKey(),
            'title' => $title, 'slug' => str($title)->slug(), 'description' => 'Pengembangan pemrograman web.',
            'provider_name' => 'Mitra', 'external_url' => 'https://example.test/pathway', 'publication_status' => 'published', 'published_at' => now()->subDay(),
            'deadline_at' => now()->addWeek(),
        ], $overrides));
    }
}
