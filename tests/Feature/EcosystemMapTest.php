<?php

namespace Tests\Feature;

use App\Models\AdministrativeArea;
use App\Models\User;
use App\Services\YouthStatisticsService;
use Database\Seeders\AdministrativeAreaSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EcosystemMapTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->seed(AdministrativeAreaSeeder::class);
    }

    public function test_public_map_suppresses_small_district_counts_and_admin_sees_exact_aggregates(): void
    {
        $district = AdministrativeArea::query()->where('code', '33.27.08')->firstOrFail();
        $youth = User::factory()->create(['name' => 'Private Map Youth']);
        $youth->assignRole('youth');
        $youth->addresses()->create(['administrative_area_id' => $district->getKey(), 'address_type' => 'domicile', 'is_primary' => true]);

        $statistics = app(YouthStatisticsService::class);
        $public = collect($statistics->publicGeography())->firstWhere('code', $district->code);
        $admin = collect($statistics->adminGeography())->firstWhere('code', $district->code);

        $this->assertNull($public['youth']);
        $this->assertSame(1, $admin['youth']);
        $this->assertSame(0, $public['community']);
        $this->assertSame(14, count($statistics->publicGeography()));

        $this->get(route('ecosystem-map.index'))
            ->assertOk()->assertSee('Peta Ekosistem Pemuda')
            ->assertSee('&lt;5', false)->assertDontSee('Private Map Youth');

        $this->get(route('admin.ecosystem-map.index'))->assertRedirect(route('login'));
        $this->actingAs($youth)->get(route('admin.ecosystem-map.index'))->assertForbidden();
        $this->actingAs(User::factory()->create()->assignRole('admin'))
            ->get(route('admin.ecosystem-map.index'))
            ->assertOk()->assertSee('Peta Ekosistem Pemuda')->assertSee('Peta Ekosistem');
    }

    public function test_geometry_matches_seeded_district_codes(): void
    {
        $geometry = json_decode(file_get_contents(public_path('data/pemalang-kecamatan.geojson')), true, flags: JSON_THROW_ON_ERROR);
        $codes = collect($geometry['features'])->pluck('properties.KDCPUM')->sort()->values()->all();
        $databaseCodes = AdministrativeArea::query()->where('area_level', 'district')->pluck('code')->sort()->values()->all();

        $this->assertSame('FeatureCollection', $geometry['type']);
        $this->assertCount(14, $codes);
        $this->assertSame($databaseCodes, $codes);
    }
}
