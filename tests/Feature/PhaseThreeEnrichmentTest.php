<?php

namespace Tests\Feature;

use App\Models\Skill;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserEducation;
use App\Services\Youth\ProfileCompletionService;
use App\Support\BinaryUuid;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\YouthEnrichmentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PhaseThreeEnrichmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([RolePermissionSeeder::class, YouthEnrichmentSeeder::class]);
    }

    private function youth(): User
    {
        return User::factory()->create()->assignRole('youth');
    }

    public function test_youth_can_sync_self_reported_skills_and_invalid_uuid_is_rejected(): void
    {
        $user = $this->youth();
        $skills = Skill::query()->limit(2)->get();

        $this->actingAs($user)->put('/youth/skills', [
            'skills' => $skills->map->uuid()->all(),
        ])->assertRedirect();

        $this->assertCount(2, $user->fresh()->skills);
        $this->assertTrue($user->fresh()->userSkills->every(fn ($skill): bool => $skill->is_self_reported));

        $this->actingAs($user)->put('/youth/skills', ['skills' => ['not-a-uuid']])
            ->assertSessionHasErrors('skills.0');
    }

    public function test_education_crud_validates_dates_and_enforces_ownership(): void
    {
        $user = $this->youth();
        $other = $this->youth();

        $this->actingAs($user)->post('/youth/educations', [
            'education_level' => 'sma_smk', 'institution_name' => 'SMAN 1 Pemalang',
            'field_of_study' => 'IPA', 'start_date' => '2022-07-01', 'end_date' => '2025-06-01',
        ])->assertRedirect();
        $education = $user->educations()->firstOrFail();

        $this->actingAs($user)->put('/youth/educations/'.$education->uuid(), [
            'education_level' => 'sma_smk', 'institution_name' => 'SMAN 2 Pemalang',
            'start_date' => '2022-07-01', 'is_current' => '1', 'end_date' => '2026-01-01',
        ])->assertRedirect();
        $this->assertSame('SMAN 2 Pemalang', $education->fresh()->institution_name);
        $this->assertNull($education->fresh()->end_date);

        $this->actingAs($other)->delete('/youth/educations/'.$education->uuid())->assertNotFound();
        $this->actingAs($user)->put('/youth/educations/not-a-uuid', [
            'education_level' => 'sma_smk', 'institution_name' => 'X',
        ])->assertNotFound();
        $this->actingAs($user)->post('/youth/educations', [
            'education_level' => 'sma_smk', 'institution_name' => 'X',
            'start_date' => '2026-01-01', 'end_date' => '2025-01-01',
        ])->assertSessionHasErrors('end_date');

        $this->actingAs($user)->delete('/youth/educations/'.$education->uuid())->assertRedirect();
        $this->assertSoftDeleted('user_educations', ['id' => $education->getKey()]);
    }

    public function test_organization_experience_crud_is_separate_from_community_membership(): void
    {
        $user = $this->youth();
        $other = $this->youth();

        $this->actingAs($user)->post('/youth/organization-experiences', [
            'organization_name' => 'OSIS SMAN 1 Pemalang', 'role_title' => 'Ketua',
            'start_date' => '2023-01-01', 'is_current' => '1',
        ])->assertRedirect();
        $experience = $user->organizationExperiences()->firstOrFail();
        $this->assertSame('OSIS SMAN 1 Pemalang', $experience->organization_name);
        $this->assertFalse(Schema::hasTable('organization_memberships'));

        $this->actingAs($user)->put('/youth/organization-experiences/'.$experience->uuid(), [
            'organization_name' => 'OSIS SMAN 1 Pemalang', 'role_title' => 'Alumni',
            'start_date' => '2023-01-01', 'end_date' => '2025-01-01',
        ])->assertRedirect();
        $this->assertSame('Alumni', $experience->fresh()->role_title);
        $this->actingAs($other)->delete('/youth/organization-experiences/'.$experience->uuid())->assertNotFound();
        $this->actingAs($user)->delete('/youth/organization-experiences/'.$experience->uuid())->assertRedirect();
    }

    public function test_self_reported_achievement_crud_cannot_set_verification_state(): void
    {
        $user = $this->youth();
        $other = $this->youth();

        $this->actingAs($user)->post('/youth/achievements', [
            'title' => 'Juara Lomba Esai', 'issuer_name' => 'Dindikpora',
            'achievement_date' => '2026-02-01', 'verification_status' => 'verified',
            'verified_by' => $other->uuid(), 'evidence_path' => '../../secret',
        ])->assertRedirect();
        $achievement = $user->achievements()->firstOrFail();
        $this->assertSame('self_reported', $achievement->verification_status);
        $this->assertNull($achievement->verified_by);
        $this->assertNull($achievement->evidence_path);

        $this->actingAs($user)->put('/youth/achievements/'.$achievement->uuid(), [
            'title' => 'Juara I Lomba Esai',
        ])->assertRedirect();
        $this->assertSame('Juara I Lomba Esai', $achievement->fresh()->title);
        $this->actingAs($other)->delete('/youth/achievements/'.$achievement->uuid())->assertNotFound();
        $this->actingAs($user)->delete('/youth/achievements/'.$achievement->uuid())->assertRedirect();
    }

    public function test_enrichment_privacy_and_profile_completion_are_derived(): void
    {
        $user = $this->youth();
        $skill = Skill::firstOrFail();

        $this->actingAs($user)->put('/youth/profile/visibility', [
            'is_profile_public' => 1, 'show_photo' => 0, 'show_bio' => 1, 'show_interests' => 1,
            'show_skills' => 0, 'show_education' => 0, 'show_organization_experience' => 0,
            'show_achievements' => 0,
        ])->assertRedirect('/youth/profile');
        $visibility = $user->fresh()->profileVisibility;
        $this->assertFalse($visibility->show_skills);
        $this->assertFalse($visibility->show_achievements);

        $this->assertSame(0, app(ProfileCompletionService::class)->calculate($user->fresh())['percentage']);
        $user->userSkills()->create([
            'id' => BinaryUuid::generate(), 'skill_id' => $skill->getKey(), 'is_self_reported' => true,
        ]);
        UserEducation::create([
            'id' => BinaryUuid::generate(), 'user_id' => $user->getKey(),
            'education_level' => 'sma_smk', 'institution_name' => 'SMAN 1 Pemalang',
        ]);
        UserAchievement::create([
            'id' => BinaryUuid::generate(), 'user_id' => $user->getKey(),
            'title' => 'Prestasi Mandiri', 'verification_status' => 'self_reported',
        ]);
        $this->assertSame(30, app(ProfileCompletionService::class)->calculate($user->fresh())['percentage']);
    }
}
