<?php

namespace Tests\Feature;

use App\Models\User;
use App\Presenters\PublicLandingPresenter;
use App\Services\PublicStatisticsService;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicLandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_is_public_and_shows_guest_authentication_actions(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Temukan ruang untuk')
            ->assertSee('Masuk')
            ->assertSee('Daftar')
            ->assertSee('/login', false)
            ->assertSee('/register', false);
    }

    public function test_stitch_landing_sections_are_rendered(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSeeInOrder([
                'Eksplorasi Sesuai Minatmu',
                'Kegiatan yang Bisa Kamu Ikuti',
                'Temukan Komunitas',
                'Opportunity untuk Pemuda',
                'Program yang Sedang Berjalan dan Selesai',
                'Bangun Portofolio Terverifikasi Sejak Dini',
                'Statistik publik SIPORA',
                'Ambil langkah pertamamu bersama SIPORA',
            ]);
    }

    public function test_authenticated_youth_can_still_access_public_landing_page(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create()->assignRole('youth');

        $this->actingAs($user)
            ->get('/')
            ->assertOk()
            ->assertSee('Ruang Saya')
            ->assertSee('/youth/home', false);
    }

    public function test_landing_content_is_explicitly_temporary_presentation_data(): void
    {
        $presentation = app(PublicLandingPresenter::class)->present()['presentation'];

        $this->assertTrue($presentation['is_placeholder']);
        $this->assertSame('mixed_landing_presentation', $presentation['source']);
        $this->assertSame(['interests', 'activities', 'communities', 'opportunities', 'programs', 'statistics'], $presentation['database_domains']);
        $this->assertSame(['portfolio_showcase'], $presentation['placeholder_domains']);
    }

    public function test_landing_uses_real_public_activity_and_community_data(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get('/')
            ->assertOk()
            ->assertSee('Workshop Web Development Pemula')
            ->assertSee('Komunitas Programmer Pemalang')
            ->assertSee('Beasiswa Pengembangan Pemuda Pemalang')
            ->assertSee('Program Pemuda Digital 2026')
            ->assertDontSee('Activity Belum Terbit');
    }

    public function test_public_statistics_match_exact_seeded_public_aggregates(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame([
            ['value' => 2, 'label' => 'Community aktif'],
            ['value' => 4, 'label' => 'Activity publik'],
            ['value' => 3, 'label' => 'Opportunity publik'],
            ['value' => 1, 'label' => 'Program selesai'],
        ], app(PublicStatisticsService::class)->summarize());
    }

    public function test_no_future_domain_migration_was_added_for_the_landing_page(): void
    {
        $migrationNames = collect(glob(database_path('migrations/*.php')))
            ->map(fn (string $path): string => strtolower(basename($path)));

        $this->assertFalse(
            $migrationNames->contains(fn (string $name): bool => str_contains($name, 'landing')),
            'The presentation-only landing page must not own a persistence migration.'
        );
    }
}
