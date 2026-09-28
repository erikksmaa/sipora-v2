<?php

namespace Tests\Feature;

use App\Models\User;
use App\Presenters\PublicLandingPresenter;
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
                'Peluang untuk Pemuda',
                'Program Unggulan Dindikpora Pemalang',
                'Bangun Portofolio Terverifikasi Sejak Dini',
                'Statistik publik SIPORA',
                'Dari Pemuda, untuk Pemalang',
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
        $this->assertSame('temporary_landing_presentation', $presentation['source']);
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
