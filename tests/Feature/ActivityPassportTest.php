<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\ActivityParticipation;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityPassportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_only_completed_accepted_participations_appear_in_private_passport(): void
    {
        $owner = $this->youth();
        $completed = $this->participation($this->activity('verified-experience'), $owner, 'accepted', 'completed');
        $this->participation($this->activity('no-show-experience'), $owner, 'accepted', 'no_show');
        $this->participation($this->activity('cancelled-experience'), $owner, 'cancelled', 'pending');
        $this->participation($this->activity('rejected-experience'), $owner, 'rejected', 'pending');

        $this->actingAs($owner)->get(route('youth.passport.index'))
            ->assertOk()->assertSee('Activity Passport')->assertSee('Activity verified-experience')
            ->assertDontSee('Activity no-show-experience')->assertDontSee('Activity cancelled-experience')->assertDontSee('Activity rejected-experience');
        $this->actingAs($owner)->get(route('youth.passport.show', $completed))
            ->assertOk()->assertSee('Pengalaman SIPORA terverifikasi')->assertSee('ID Activity')->assertSee('Belum ada sertifikat yang diterbitkan');
    }

    public function test_multiple_completed_activities_render_and_youth_home_uses_real_count(): void
    {
        $owner = $this->youth();
        foreach (['first-passport', 'second-passport'] as $slug) {
            $this->participation($this->activity($slug), $owner, 'accepted', 'completed');
        }

        $this->actingAs($owner)->get(route('youth.passport.index'))->assertOk()
            ->assertSee('Activity first-passport')->assertSee('Activity second-passport')->assertSee('2');
        $this->actingAs($owner)->get(route('youth.home'))->assertOk()
            ->assertSee('Activity Passport')->assertSee('Activity selesai')->assertSee('2');
    }

    public function test_passport_detail_is_owner_only_and_guest_is_blocked(): void
    {
        $owner = $this->youth();
        $other = $this->youth();
        $entry = $this->participation($this->activity('private-entry'), $owner, 'accepted', 'completed');

        $this->get(route('youth.passport.index'))->assertRedirectToRoute('login');
        $this->actingAs($other)->get(route('youth.passport.show', $entry))->assertNotFound();
        $this->actingAs($owner)->get(route('youth.passport.show', $entry))->assertOk();
    }

    public function test_non_completed_entry_cannot_be_opened_directly(): void
    {
        $owner = $this->youth();
        foreach ([['accepted', 'no_show'], ['accepted', 'pending'], ['cancelled', 'pending'], ['rejected', 'pending']] as $index => [$registration, $completion]) {
            $entry = $this->participation($this->activity('hidden-'.$index), $owner, $registration, $completion);
            $this->actingAs($owner)->get(route('youth.passport.show', $entry))->assertNotFound();
        }
    }

    private function activity(string $slug): Activity
    {
        $creator = $this->youth();
        $organizationCategory = OrganizationCategory::firstOrCreate(['slug' => 'passport-community'], ['name' => 'Komunitas Passport']);
        $organization = Organization::firstOrCreate(['slug' => 'passport-organization'], [
            'created_by_user_id' => $creator->getKey(), 'category_id' => $organizationCategory->getKey(), 'name' => 'Komunitas Passport',
            'description' => 'Penyelenggara pengalaman.', 'social_links' => (object) [], 'review_status' => Organization::REVIEW_APPROVED,
            'operational_status' => Organization::OPERATIONAL_ACTIVE, 'approved_at' => now(),
        ]);
        $category = ActivityCategory::firstOrCreate(['slug' => 'passport-category'], ['name' => 'Kategori Passport']);

        return Activity::create(['organization_id' => $organization->getKey(), 'category_id' => $category->getKey(), 'created_by_user_id' => $creator->getKey(),
            'title' => 'Activity '.$slug, 'slug' => $slug, 'description' => 'Pengalaman Passport.', 'location_type' => 'offline', 'venue_name' => 'Pemalang',
            'start_at' => now()->subDays(3), 'end_at' => now()->subDays(2), 'registration_mode' => 'open', 'review_status' => Activity::REVIEW_APPROVED,
            'publication_status' => Activity::PUBLICATION_PUBLISHED, 'execution_status' => Activity::EXECUTION_COMPLETED, 'published_at' => now()->subWeek()]);
    }

    private function participation(Activity $activity, User $user, string $registration, string $completion): ActivityParticipation
    {
        return ActivityParticipation::create(['activity_id' => $activity->getKey(), 'user_id' => $user->getKey(), 'activity_role' => 'participant',
            'registration_status' => $registration, 'completion_status' => $completion,
            'completed_at' => $completion === 'completed' ? now() : null, 'requested_at' => now()->subWeek()]);
    }

    private function youth(): User
    {
        $user = User::factory()->create();
        $user->assignRole('youth');

        return $this->completeYouthOnboarding($user);
    }
}
