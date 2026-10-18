<?php

namespace Database\Seeders;

use App\Models\AdministrativeArea;
use App\Models\Interest;
use App\Models\User;
use App\Models\UserInterest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class DemoYouthOnboardingSeeder extends Seeder
{
    public function run(): void
    {
        if (! ((app()->environment('local') && DB::connection()->getDatabaseName() === 'sipora')
            || (app()->environment('testing') && DB::connection()->getDatabaseName() === 'sipora_regression_test'))) {
            throw new RuntimeException('Demo onboarding hanya untuk local sipora atau testing.');
        }
        $this->call(PemalangVillageSeeder::class);
        $users = User::role('youth')->where(function ($query): void {
            $query->where('email', 'youth1@sipora.test')->orWhere('email', 'like', 'demo.youth%@sipora.test')->orWhere('email', 'like', 'asset.y%@sipora.test');
        })->with(['profile', 'primaryDomicile'])->get();
        foreach ($users as $user) {
            $profile = $user->profile;
            if (! $profile) {
                continue;
            }
            $profile->fill([
                'birth_place' => $profile->birth_place ?: 'Pemalang',
                'birth_date' => $profile->birth_date ?: now()->subYears(20)->startOfYear()->addDays(11),
                'gender' => $profile->gender ?: 'male',
            ])->save();
            $address = $user->primaryDomicile;
            if ($address) {
                $village = AdministrativeArea::where('area_level', 'village')->where('parent_id', $address->administrative_area_id)->orderBy('code')->firstOrFail();
                if (! $address->village_id || ! AdministrativeArea::whereKey($address->village_id)->where('parent_id', $address->administrative_area_id)->exists()) {
                    $address->update(['village_id' => $village->getKey()]);
                }
            }
            if (! $user->interests()->exists()) {
                UserInterest::withTrashed()->updateOrCreate(['user_id' => $user->getKey(), 'interest_id' => Interest::where('slug', 'teknologi')->firstOrFail()->getKey()], ['deleted_at' => null]);
            }
        }
        $this->call(DemoIdentityAnchorSeeder::class);
    }
}
