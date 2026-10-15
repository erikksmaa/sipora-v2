<?php

namespace Tests\Support;

use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationMembership;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\User;
use Illuminate\Support\Str;

trait ProgramIntakeFixtures
{
    protected function intakeFixture(array $override = []): array
    {
        $manager = $this->completeYouthOnboarding(User::factory()->create()->assignRole('youth'));
        $slug = 'intake-'.Str::lower(Str::random(10));
        $category = OrganizationCategory::create(['name' => 'QA Category', 'slug' => $slug]);
        $org = Organization::create(['created_by_user_id' => $manager->getKey(), 'category_id' => $category->getKey(), 'name' => 'QA Community', 'slug' => $slug, 'review_status' => 'approved', 'operational_status' => 'active', 'approved_at' => now()]);
        OrganizationMembership::create(['organization_id' => $org->getKey(), 'user_id' => $manager->getKey(), 'membership_status' => 'active', 'access_role' => 'leader', 'approved_at' => now()]);
        $programCategory = ProgramCategory::create(['name' => 'QA Program', 'slug' => $slug]);
        $program = Program::create(array_merge(['organization_id' => $org->getKey(), 'category_id' => $programCategory->getKey(), 'created_by_user_id' => $manager->getKey(), 'title' => 'QA Program Intake', 'slug' => $slug, 'execution_status' => 'running', 'registration_enabled' => true, 'selection_mode' => 'review', 'quota' => 1], $override));
        $program->proposals()->create(['version' => 1, 'status' => 'approved', 'submitted_at' => now(), 'reviewed_at' => now()]);

        return [$org, $manager, $program];
    }

    protected function intakeYouth(): User
    {
        return $this->completeYouthOnboarding(User::factory()->create()->assignRole('youth'));
    }
}
