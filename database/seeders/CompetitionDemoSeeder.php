<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class CompetitionDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Refuse production and every non-development database before writing anything.
        if (! app()->environment(['local', 'testing']) || (! app()->environment('testing') && DB::connection()->getDatabaseName() !== 'sipora')) {
            throw new RuntimeException('Competition demo is only allowed in local sipora or an isolated testing environment.');
        }
        if (\App\Support\Demo\DemoAssetManifest::enabled()) {
            \App\Support\Demo\DemoAssetManifest::preflight();
        }
        DB::transaction(function (): void {
            $this->call([RolePermissionSeeder::class, AdministrativeAreaSeeder::class, InterestSeeder::class, SkillSeeder::class,
                ActivityCategorySeeder::class, OpportunityCategorySeeder::class, ProgramCategorySeeder::class, ForumCategorySeeder::class]);
            // Existing development accounts are preserved; bootstrap only if not already present.
            foreach (['admin' => 'Admin SIPORA', 'verifier' => 'Verifikator Dindikpora'] as $role => $name) {
                $user = User::firstOrCreate(['email' => $role.'@sipora.test'], ['name' => $name, 'password' => Hash::make(UserSeeder::PASSWORD)]);
                if ($user->wasRecentlyCreated) {
                    $user->forceFill(['email_verified_at' => now()])->save();
                    $user->assignRole($role);
                }
            }
            $this->call([DemoYouthSeeder::class, DemoEcosystemSeeder::class, DemoDiscussionSeeder::class, DemoChatSeeder::class]);
            if (app()->environment('local') && is_file(base_path('output/pdf/07A_PROGRAM_PROPOSALS/manifest.json'))) {
                $this->call(DemoProposalStatusSeeder::class);
            }
            if (app()->environment('local') && is_file(public_path('media/demo/07C_PROGRAM_LOGBOOK/manifest.json'))) {
                $this->call(DemoProgramLogbookSeeder::class);
            }
            if (app()->environment('local') && is_file(base_path('output/pdf/07D_ELPJ/manifest.json'))) {
                $this->call(DemoProgramFinancialReportSeeder::class);
            }
            if (app()->environment('local') && is_file(base_path('output/demo-assets/final-evaluation-scenario.json'))) {
                $this->call(DemoFinalEvaluationSeeder::class);
            }
            if (app()->environment('local') && is_file(public_path('media/demo/08A_SOCIAL_FEED/manifest.json'))) {
                if (\App\Support\Demo\DemoAssetManifest::enabled()) \App\Support\Demo\DemoAssetManifest::normalizeExecutionState();
                $this->call(DemoSocialFeedMediaSeeder::class);
            }
            if (app()->environment('local') && is_file(public_path('media/demo/08A_SOCIAL_FEED/manifest.json'))
                && is_file(base_path('output/demo-assets/final-evaluation-scenario.json'))) {
                $this->call(DemoNotificationSeeder::class);
            }
        });
        $this->command?->info('Competition demo ready. Youth: youth1@sipora.test');
    }
}
