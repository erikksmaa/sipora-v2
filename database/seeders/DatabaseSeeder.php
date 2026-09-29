<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        if (app()->environment('production')) {
            $this->command?->warn('Development demo data was not seeded in production.');

            return;
        }

        $this->call([
            AdministrativeAreaSeeder::class,
            InterestSeeder::class,
            SkillSeeder::class,
            ActivityCategorySeeder::class,
            UserSeeder::class,
            UserProfileSeeder::class,
            UserEnrichmentSeeder::class,
            IdentityVerificationSeeder::class,
            OrganizationSeeder::class,
            OrganizationMembershipSeeder::class,
            ProgramCategorySeeder::class,
            ProgramSeeder::class,
            ActivitySeeder::class,
            ActivitySessionSeeder::class,
            ActivityParticipationSeeder::class,
            ActivityAttendanceSeeder::class,
            UserCertificateSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
