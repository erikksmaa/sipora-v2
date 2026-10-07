<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserIdentity;
use App\Models\UserProfile;
use App\Models\UserProfileVisibility;
use App\Services\YouthStatisticsService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class YouthStatisticsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_public_summary_hides_small_headline_counts(): void
    {
        $user = User::factory()->create();
        $user->assignRole('youth');
        $user->identity()->create(['verification_status' => UserIdentity::STATUS_VERIFIED]);

        $statistics = app(YouthStatisticsService::class);

        $this->assertSame(1, $statistics->summary()['registered']);
        $this->assertNull($statistics->publicSummary()['registered']);
        $this->assertNull($statistics->publicSummary()['identity_verified']);
        $this->assertSame(0, $statistics->publicSummary()['activity_participated']);
        $this->get(route('home'))->assertOk()->assertSee('&lt;5', false);
    }

    public function test_public_statistics_share_real_totals_and_suppress_small_buckets(): void
    {
        for ($number = 1; $number <= 6; $number++) {
            $user = User::factory()->create(['name' => 'Private Youth '.$number, 'created_at' => $number === 6 ? now()->subYear() : now()]);
            $user->assignRole('youth');
            UserProfile::create([
                'user_id' => $user->getKey(), 'public_slug' => 'private-youth-'.$number,
                'full_name' => 'Private Youth '.$number, 'birth_date' => '2002-01-01',
                'gender' => $number === 6 ? 'female' : 'male',
            ]);
            if ($number <= 5) {
                $user->identity()->create(['verification_status' => UserIdentity::STATUS_VERIFIED]);
                UserProfileVisibility::create(['user_id' => $user->getKey(), 'is_profile_public' => true]);
            }
        }
        User::factory()->create(['name' => 'Private Admin'])->assignRole('admin');

        $service = app(YouthStatisticsService::class);
        $this->assertSame(['registered' => 6, 'identity_verified' => 5, 'public_portfolio' => 5, 'activity_participated' => 0, 'certificate' => 0], $service->summary());
        $public = $service->public();
        $admin = $service->admin();
        $this->assertSame(5, collect($public['gender'])->firstWhere('label', 'male')['count']);
        $this->assertNull(collect($public['gender'])->firstWhere('label', 'female')['count']);
        $this->assertSame(1, collect($admin['gender'])->firstWhere('label', 'female')['count']);
        $this->assertSame($service->summary(), $public['totals']);
        $this->assertSame($service->summary(), $service->publicSummary());
        $this->assertSame(0, collect($public['participation'])->firstWhere('label', 'Pernah mendaftar Activity')['count']);
        $this->assertArrayHasKey('coverage', $public);
        $this->assertArrayHasKey('interest_by_district', $admin['cross_insights']);
        $this->assertArrayHasKey('skill_by_age', $admin['cross_insights']);
        $this->assertArrayHasKey('completion_by_category', $admin['cross_insights']);
        $this->assertCount(12, $public['growth_monthly']);
        $this->assertSame(0, $public['growth_monthly'][0]['count']);
        $this->assertSame(0, collect($public['funnel'])->firstWhere('label', 'Mengikuti Activity')['count']);
        $this->assertSame(5, $service->public(['year' => now()->year])['totals']['registered']);
        $this->assertSame(0, $service->public(['year' => now()->year])['totals']['certificate']);
        $this->assertNull($service->public(['year' => now()->subYear()->year])['totals']['registered']);
        $this->assertArrayHasKey('ecosystem', $public);

        $this->get(route('statistics.index'))->assertOk()->assertSee('Statistik Pemuda')->assertSee('Berdasarkan pemuda yang terdaftar di SIPORA')->assertSee('data-chart="growth"', false)->assertDontSee('Private Youth 1');
        $this->get(route('statistics.index', ['year' => now()->subYear()->year]))->assertOk()->assertViewHas('statistics', fn (array $data): bool => $data['totals']['registered'] === null);
        $this->get(route('youth-directory.index'))->assertOk()->assertSee('Pemuda Terdaftar')->assertSee('Direktori ini hanya menampilkan Portfolio');
        $this->get(route('home'))->assertOk()->assertSee('Ekosistem SIPORA dalam Angka')->assertSee('Lihat Statistik Pemuda');
    }
}
