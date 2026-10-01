<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class QualityAssuranceSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_qa_dataset_populates_every_eligible_application_table_with_at_least_ten_rows(): void
    {
        $this->seed(DatabaseSeeder::class);

        $tables = [
            'users', 'user_social_accounts', 'user_profiles', 'administrative_areas', 'user_addresses',
            'user_identities', 'user_identity_verifications', 'interests', 'user_interests', 'skills',
            'user_skills', 'user_educations', 'organization_experiences', 'user_achievements',
            'user_profile_visibility', 'organization_categories', 'organizations',
            'organization_verification_requests', 'organization_memberships', 'program_categories',
            'programs', 'activity_categories', 'activities', 'activity_reviews', 'activity_sessions',
            'activity_participations', 'activity_attendances', 'user_certificates', 'program_proposals',
            'program_logbooks', 'program_logbook_media', 'financial_reports', 'financial_items',
            'program_evaluations', 'opportunity_categories', 'opportunities', 'opportunity_bookmarks',
            'notifications', 'activity_log', 'permissions', 'model_has_roles', 'role_has_permissions',
        ];

        foreach ($tables as $table) {
            $this->assertGreaterThanOrEqual(10, DB::table($table)->count(), $table.' must contain at least ten QA rows.');
        }
    }

    public function test_qa_dataset_preserves_locked_global_role_and_permission_architecture(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(['admin', 'verifier', 'youth'], DB::table('roles')->orderBy('name')->pluck('name')->all());
        $this->assertSame(0, DB::table('model_has_permissions')->count());
        $this->assertDatabaseMissing('roles', ['name' => 'leader']);
        $this->assertDatabaseMissing('roles', ['name' => 'manager']);
        $this->assertDatabaseMissing('roles', ['name' => 'member']);
    }
}
