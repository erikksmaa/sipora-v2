<?php

namespace Tests;

use App\Models\AdministrativeArea;
use App\Models\Interest;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserIdentity;
use App\Models\UserInterest;
use App\Models\UserSkill;
use App\Services\Youth\IdentityAnchorService;
use App\Support\BinaryUuid;
use Database\Seeders\AdministrativeAreaSeeder;
use Database\Seeders\InterestSeeder;
use Database\Seeders\PemalangVillageSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Str;
use Tests\Support\TestEnvironmentGuard;

abstract class TestCase extends BaseTestCase
{
    private static int $nikSerial = 0;

    public function createApplication()
    {
        $app = parent::createApplication();
        TestEnvironmentGuard::assertSafe(
            $app->environment(),
            $app['config']->get('database.default'),
            $app['db']->connection()->getDatabaseName(),
        );

        // Seeders also write fixture files. Never let them touch demo storage.
        foreach ($app['config']->get('filesystems.disks') as $name => $disk) {
            if (($disk['driver'] ?? null) === 'local') {
                $app['config']->set("filesystems.disks.$name.root", storage_path("framework/testing/disks/$name"));
            }
        }

        return $app;
    }

    protected function registrationPayload(array $overrides = []): array
    {
        static $serial = 1;
        $this->seed([AdministrativeAreaSeeder::class, PemalangVillageSeeder::class, InterestSeeder::class]);
        $village = AdministrativeArea::where('area_level', 'village')->firstOrFail();
        $date = now()->subYears(20);

        return array_replace(['name' => 'Youth Pendaftaran', 'email' => 'pendaftaran-'.$serial.'@example.test',
            'password' => 'test-only-password', 'password_confirmation' => 'test-only-password', 'recaptcha_token' => 'test',
            'national_id' => '000000'.$date->format('dmy').str_pad((string) $serial++, 4, '0', STR_PAD_LEFT),
            'birth_place' => 'Pemalang', 'birth_date' => $date->toDateString(), 'gender' => 'male', 'administrative_area_id' => BinaryUuid::text($village->parent_id),
            'village_id' => $village->uuid(), 'interests' => [Interest::firstOrFail()->uuid()],
        ], $overrides);
    }

    protected function registrationSteps(array $data): array
    {
        for ($step = 0; $step < 3; $step++) {
            $this->postJson(route('registration.step', ['step' => $step]), $data)
                ->assertOk()->assertJsonPath('next_step', $step + 1);
        }

        return $data;
    }

    protected function completeYouthOnboarding(User $user, string $through = 'complete'): User
    {
        $user->profile()->firstOrCreate([], [
            'public_slug' => 'onboarding-test-'.Str::lower(Str::random(12)),
            'full_name' => $user->name,
            'birth_date' => '2001-01-01',
        ]);
        $area = AdministrativeArea::query()->where('area_level', 'district')->first()
            ?? AdministrativeArea::create(['code' => 'TEST-ONBOARDING', 'name' => 'Pemalang', 'area_level' => 'district']);
        $user->primaryDomicile()->firstOrCreate([], ['administrative_area_id' => $area->getKey(), 'address_type' => 'domicile', 'is_primary' => true]);

        if (! $user->identity()->first()?->nationalId()) {
            app(IdentityAnchorService::class)->assign($user, '000000010101'.str_pad((string) (++self::$nikSerial), 4, '0', STR_PAD_LEFT));
        }

        if ($through === 'biodata') {
            return $user;
        }

        $user->identity()->updateOrCreate([], ['verification_status' => UserIdentity::STATUS_VERIFIED]);
        if ($through === 'identity') {
            return $user;
        }

        $user->profile()->update(['bio' => 'Pemuda aktif di SIPORA.', 'occupation_status' => 'student']);
        if ($through === 'profile') {
            return $user;
        }

        $skill = Skill::query()->first() ?? Skill::create(['slug' => 'test-onboarding-skill', 'name' => 'Keahlian Uji']);
        $interest = Interest::query()->first() ?? Interest::create(['slug' => 'test-onboarding-interest', 'name' => 'Minat Uji']);
        UserSkill::firstOrCreate(['user_id' => $user->getKey(), 'skill_id' => $skill->getKey()], ['is_self_reported' => true]);
        UserInterest::firstOrCreate(['user_id' => $user->getKey(), 'interest_id' => $interest->getKey()]);

        return $user;
    }
}
