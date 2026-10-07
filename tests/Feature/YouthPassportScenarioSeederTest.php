<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityParticipation;
use App\Models\User;
use App\Models\UserCertificate;
use App\Services\Activity\ActivityPassportService;
use App\Services\Youth\YouthOnboardingService;
use App\Services\Youth\YouthPortfolioService;
use Database\Seeders\ActivityCategorySeeder;
use Database\Seeders\AdministrativeAreaSeeder;
use Database\Seeders\InterestSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SkillSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\YouthPassportScenarioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class YouthPassportScenarioSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeded_passport_uses_real_records_and_remains_idempotent(): void
    {
        $this->seed([
            RolePermissionSeeder::class, AdministrativeAreaSeeder::class, InterestSeeder::class,
            SkillSeeder::class, ActivityCategorySeeder::class, UserSeeder::class,
        ]);
        $this->seed(YouthPassportScenarioSeeder::class);
        $this->seed(YouthPassportScenarioSeeder::class);

        $youth = User::query()->where('email', YouthPassportScenarioSeeder::EMAIL)->firstOrFail();
        $this->assertSame('complete', app(YouthOnboardingService::class)->state($youth)['currentStep']);
        $entries = app(ActivityPassportService::class)->queryFor($youth)->get();
        $this->assertCount(5, $entries);
        $this->assertCount(5, $entries->pluck('activity_id')->unique());
        $this->assertSame([
            'passport-bootcamp-desain-konten-digital',
            'passport-seminar-kewirausahaan-digital',
            'passport-volunteer-pemalang-youth-festival',
            'passport-pelatihan-public-speaking-dasar',
            'workshop-web-development-untuk-pemuda',
        ], $entries->pluck('activity.slug')->all());
        $this->assertSame([
            [3, 1, 0, 4], [1, 0, 0, 1], [1, 0, 0, 1], [2, 0, 0, 2], [3, 0, 0, 3],
        ], $entries->map(fn (ActivityParticipation $entry): array => [
            $entry->present_count, $entry->absent_count, $entry->excused_count, $entry->activity->sessions_count,
        ])->all());
        $this->assertSame('volunteer', $entries[2]->activity_role);
        $this->assertNull($entries[0]->certificate);
        $this->assertSame(4, $entries->filter(fn (ActivityParticipation $entry): bool => $entry->certificate !== null)->count());
        $this->assertSame(11, $entries->sum(fn (ActivityParticipation $entry): int => $entry->attendances()->count()));
        $this->assertSame(4, UserCertificate::query()->where('user_id', $youth->getKey())->count());

        $this->actingAs($youth)->get(route('youth.passport.index'))->assertOk()
            ->assertSee('Activity Passport')->assertSee('3 dari 3 sesi hadir')
            ->assertSee('3 dari 4 sesi hadir')->assertSee('Volunteer Pemalang Youth Festival')
            ->assertSeeInOrder(['Bootcamp Desain Konten Digital', 'Seminar Kewirausahaan Digital',
                'Volunteer Pemalang Youth Festival', 'Pelatihan Public Speaking Dasar', 'Workshop Web Development untuk Pemuda']);
        $this->actingAs($youth)->get(route('youth.passport.show', $entries[4]))->assertOk()
            ->assertSee('3 dari 3 sesi hadir')->assertSee('Sertifikat tersedia')
            ->assertSee(route('youth.certificates.show', $entries[4]->certificate));
        $this->actingAs($youth)->get(route('youth.passport.show', $entries[0]))->assertOk()
            ->assertSee('3 dari 4 sesi hadir')->assertSee('Belum ada sertifikat yang diterbitkan');

        $public = app(YouthPortfolioService::class)->forPublicSlug('nadira-putri-passport-demo');
        $this->assertCount(5, $public['activities']);
        $this->assertCount(4, $public['certificates']);
        $this->get(route('portfolio.show', 'nadira-putri-passport-demo'))->assertOk()
            ->assertSee('Volunteer Pemalang Youth Festival')->assertSee('Pengalaman Terverifikasi SIPORA');
        $youth->profileVisibility()->update(['show_activity_passport' => false, 'show_certificates' => false]);
        $hidden = app(YouthPortfolioService::class)->forPublicSlug('nadira-putri-passport-demo');
        $this->assertSame([], $hidden['activities']);
        $this->assertSame([], $hidden['certificates']);
        $this->get(route('portfolio.show', 'nadira-putri-passport-demo'))->assertOk()
            ->assertDontSee('Volunteer Pemalang Youth Festival');
        $this->assertCount(5, app(YouthPortfolioService::class)->forOwner($youth)['activities']);

        $this->assertSame(5, ActivityParticipation::query()->where('user_id', $youth->getKey())
            ->whereHas('activity', fn ($activity) => $activity->whereIn('slug', $entries->pluck('activity.slug')))->count());
        $this->assertFalse(Activity::query()->where('slug', 'passport-bootcamp-desain-konten-digital')->firstOrFail()->certificate_enabled);
    }
}
