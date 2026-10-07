<?php

namespace Tests;

use App\Models\AdministrativeArea;
use App\Models\Interest;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserIdentity;
use App\Models\UserInterest;
use App\Models\UserSkill;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Str;

abstract class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        $app = parent::createApplication();
        if ($app['config']->get('database.default') !== 'mysql'
            || $app['config']->get('database.connections.mysql.database') !== 'sipora') {
            throw new \RuntimeException('Tests may only run against the canonical local sipora MySQL database. Clear cached config first.');
        }

        return $app;
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
