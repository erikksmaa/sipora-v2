<?php

namespace Tests\Feature;

use App\Models\AdministrativeArea;
use App\Models\Opportunity;
use App\Models\OpportunityCategory;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class OpportunityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_admin_can_create_update_and_publish_an_opportunity(): void
    {
        $admin = $this->roleUser('admin');
        [$category, $area] = $this->masters();
        $payload = $this->payload($category, $area);

        $this->actingAs($admin)->post(route('admin.opportunities.store'), $payload)->assertRedirect();
        $opportunity = Opportunity::firstOrFail();
        $this->assertSame(Opportunity::STATUS_DRAFT, $opportunity->publication_status);
        $this->assertTrue(hash_equals($admin->getKey(), $opportunity->created_by_user_id));

        $this->actingAs($admin)->patch(route('admin.opportunities.update', $opportunity), [...$payload, 'title' => 'Beasiswa Pemuda Diperbarui'])->assertRedirect();
        $this->actingAs($admin)->post(route('admin.opportunities.publish', $opportunity))->assertRedirect();
        $this->assertSame(Opportunity::STATUS_PUBLISHED, $opportunity->fresh()->publication_status);
        $this->assertNotNull($opportunity->fresh()->published_at);
        $this->assertDatabaseHas('activity_log', ['event' => 'opportunity_published']);
    }

    public function test_only_admin_can_manage_opportunities_and_input_is_validated(): void
    {
        $youth = $this->roleUser('youth');
        $verifier = $this->roleUser('verifier');
        [$category, $area] = $this->masters();
        $payload = $this->payload($category, $area);

        $this->post(route('admin.opportunities.store'), $payload)->assertRedirect(route('login'));
        $this->actingAs($youth)->post(route('admin.opportunities.store'), $payload)->assertForbidden();
        $this->actingAs($verifier)->post(route('admin.opportunities.store'), $payload)->assertForbidden();
        $this->actingAs($this->roleUser('admin'))->post(route('admin.opportunities.store'), [...$payload, 'external_url' => 'javascript:alert(1)', 'ends_at' => now()->subDay()->toDateTimeString()])->assertSessionHasErrors(['external_url', 'ends_at']);
    }

    public function test_public_discovery_exposes_only_published_records_and_supports_search_and_deadline_order(): void
    {
        [$category, $area] = $this->masters();
        $later = $this->opportunity($category, $area, 'Magang Digital Terbuka', 'magang-later', 20);
        $earlier = $this->opportunity($category, $area, 'Beasiswa Digital Pemuda', 'beasiswa-earlier', 5);
        $draft = $this->opportunity($category, $area, 'Rahasia Internal', 'rahasia-internal', 2, Opportunity::STATUS_DRAFT);

        $this->get(route('opportunities.index'))->assertOk()->assertSeeInOrder([$earlier->title, $later->title])->assertDontSee($draft->title);
        $this->get(route('opportunities.index', ['q' => 'Beasiswa Digital Pemuda']))->assertOk()->assertSee($earlier->title)->assertDontSee($later->title);
        $this->get(route('opportunities.show', $earlier))->assertOk()->assertSee('Proses pendaftaran');
        $this->get(route('opportunities.show', $draft))->assertNotFound();
    }

    public function test_youth_can_bookmark_public_opportunity_without_accessing_another_users_saved_list(): void
    {
        [$category, $area] = $this->masters();
        $opportunity = $this->opportunity($category, $area, 'Volunteer Pemuda', 'volunteer-pemuda', 10);
        $owner = $this->roleUser('youth');
        $other = $this->roleUser('youth');

        $this->actingAs($owner)->post(route('youth.opportunities.bookmark', $opportunity))->assertRedirect();
        $this->actingAs($owner)->post(route('youth.opportunities.bookmark', $opportunity))->assertSessionHasErrors('bookmark');
        $this->actingAs($owner)->get(route('youth.opportunities.bookmarks'))->assertOk()->assertSee($opportunity->title);
        $this->actingAs($other)->get(route('youth.opportunities.bookmarks'))->assertOk()->assertDontSee($opportunity->title);
        $this->actingAs($owner)->delete(route('youth.opportunities.bookmark.destroy', $opportunity))->assertRedirect();
        $this->assertSoftDeleted('opportunity_bookmarks', ['opportunity_id' => $opportunity->getKey(), 'user_id' => $owner->getKey()]);
    }

    public function test_approved_schema_tables_and_binary_identifiers_are_present(): void
    {
        $this->assertTrue(Schema::hasTable('opportunity_categories'));
        $this->assertTrue(Schema::hasTable('opportunities'));
        $this->assertTrue(Schema::hasTable('opportunity_bookmarks'));
        [$category, $area] = $this->masters();
        $opportunity = $this->opportunity($category, $area, 'UUID Opportunity', 'uuid-opportunity', 4);
        $this->assertSame(16, strlen($opportunity->getKey()));
        $this->assertSame(36, strlen($opportunity->uuid()));
    }

    private function masters(): array
    {
        return [
            OpportunityCategory::create(['name' => 'Beasiswa', 'slug' => 'beasiswa']),
            AdministrativeArea::create(['code' => '33.27.08', 'name' => 'Pemalang', 'area_level' => 'district']),
        ];
    }

    private function payload(OpportunityCategory $category, AdministrativeArea $area): array
    {
        return ['category_id' => $category->uuid(), 'administrative_area_id' => $area->uuid(), 'title' => 'Beasiswa Pemuda', 'provider_name' => 'Dindikpora', 'description' => 'Peluang pengembangan pemuda.', 'location_text' => 'Pemalang', 'external_url' => 'https://example.test/daftar', 'deadline_at' => now()->addWeek()->toDateTimeString(), 'starts_at' => now()->addWeeks(2)->toDateTimeString(), 'ends_at' => now()->addWeeks(3)->toDateTimeString()];
    }

    private function opportunity(OpportunityCategory $category, AdministrativeArea $area, string $title, string $slug, int $deadlineDays, string $status = Opportunity::STATUS_PUBLISHED): Opportunity
    {
        return Opportunity::create(['category_id' => $category->getKey(), 'title' => $title, 'slug' => $slug, 'provider_name' => 'Penyedia', 'description' => 'Peluang publik.', 'administrative_area_id' => $area->getKey(), 'external_url' => 'https://example.test/'.$slug, 'deadline_at' => now()->addDays($deadlineDays), 'publication_status' => $status, 'published_at' => $status === Opportunity::STATUS_PUBLISHED ? now()->subMinute() : null]);
    }

    private function roleUser(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }
}
