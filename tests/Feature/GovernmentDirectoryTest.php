<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\AdministrativeArea;
use App\Models\User;
use App\Services\AdminAnalyticsService;
use App\Services\GovernmentDirectoryService;
use App\Services\VerifierQueueSummaryService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\Support\ProgramIntakeFixtures;
use Tests\TestCase;

class GovernmentDirectoryTest extends TestCase
{
    use ProgramIntakeFixtures, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_government_directories_include_registered_records_and_authorized_read_details(): void
    {
        [$org, $youth, $program] = $this->intakeFixture();
        $activity = $this->activity($org, $youth, $program);
        foreach (['admin', 'verifier'] as $workspace) {
            $actor = User::factory()->create()->assignRole($workspace);
            foreach (['youth' => $youth->uuid(), 'communities' => $org->slug, 'activities' => $activity->slug, 'programs' => $program->slug] as $kind => $key) {
                $this->actingAs($actor)->get(route($workspace.'.directory.'.$kind.'.index'))->assertOk()->assertSee('Data Ekosistem')->assertSee('w-full min-w-[960px]', false);
                $this->get(route($workspace.'.directory.'.$kind.'.show', $key))->assertOk()->assertSee('Ringkasan baca saja');
            }
        }
        $org->update(['review_status' => 'draft', 'operational_status' => 'inactive', 'approved_at' => null]);
        $this->get(route('verifier.directory.communities.index'))->assertOk()->assertSee($org->name);
        $this->get(route('verifier.directory.activities.index'))->assertOk()->assertSee($activity->title);
    }

    public function test_youth_and_wrong_government_role_cannot_access_directories_or_admin_actions(): void
    {
        [$org, $youth] = $this->intakeFixture();
        foreach (['admin', 'verifier'] as $workspace) {
            foreach (['youth', 'communities', 'activities', 'programs'] as $kind) {
                $this->actingAs($youth)->get(route($workspace.'.directory.'.$kind.'.index'))->assertForbidden();
                $this->get(route($workspace.'.directory.'.$kind.'.show', $kind === 'youth' ? $youth->uuid() : $org->slug))->assertForbidden();
            }
        }
        $verifier = User::factory()->create()->assignRole('verifier');
        $this->actingAs($verifier)->get(route('admin.directory.youth.index'))->assertForbidden();
        $this->get(route('admin.identity-verifications.index'))->assertForbidden();
        $this->patch('/admin/directory/youth/'.$youth->uuid(), ['name' => 'Changed'])->assertStatus(405);
        $this->assertNotSame('Changed', $youth->fresh()->name);
        $this->expectException(HttpException::class);
        app(GovernmentDirectoryService::class)->listing($youth, 'verifier', 'youth', []);
    }

    public function test_private_youth_projection_and_search_do_not_expose_hidden_information(): void
    {
        [$org, $youth] = $this->intakeFixture();
        $youth->profile()->update(['full_name' => 'Secret Youth Name', 'phone' => '081234567890', 'birth_date' => '2001-07-09', 'bio' => 'HIDDEN BIO']);
        $youth->profileVisibility()->create(['is_profile_public' => false, 'show_skills' => false, 'show_interests' => false, 'show_activity_passport' => false, 'show_community_membership' => false]);
        $verifier = User::factory()->create()->assignRole('verifier');
        $this->actingAs($verifier)->get(route('verifier.directory.youth.index'))->assertOk()->assertDontSee('Secret Youth Name')->assertDontSee($youth->email)->assertDontSee('081234567890')->assertDontSee('HIDDEN BIO')->assertDontSee('2001-07-09')->assertDontSee('onboarding-test-')->assertDontSee('Keahlian Uji');
        $this->get(route('verifier.directory.youth.show', $youth->uuid()))->assertOk()->assertDontSee('Secret Youth Name')->assertDontSee('Status identitas')->assertDontSee('Verifikasi email')->assertSee('Privat');
        $this->get(route('verifier.directory.youth.index', ['q' => 'Secret Youth Name']))->assertOk()->assertViewHas('records', fn ($records) => $records->total() === 0);
        $this->getJson(route('verifier.directory.youth.index', ['identity' => 'verified']))->assertUnprocessable();
        $admin = User::factory()->create()->assignRole('admin');
        $this->actingAs($admin)->get(route('admin.directory.youth.show', $youth->uuid()))->assertOk()->assertSee('Secret Youth Name')->assertSee('Status identitas')->assertDontSee('081234567890')->assertDontSee($youth->email)->assertDontSee('HIDDEN BIO');
        $youth->profileVisibility()->update(['is_profile_public' => true, 'show_skills' => false, 'show_interests' => false]);
        $this->actingAs($verifier)->get(route('verifier.directory.youth.show', $youth->uuid()))->assertOk()->assertSee('Secret Youth Name')->assertDontSee('Keahlian Uji');
    }

    public function test_filters_pagination_and_empty_states_are_factual(): void
    {
        [$org, $youth] = $this->intakeFixture();
        User::factory()->count(17)->create()->each(fn ($u) => $u->assignRole('youth'));
        $admin = User::factory()->create()->assignRole('admin');
        $this->actingAs($admin)->get(route('admin.directory.youth.index'))->assertOk()->assertViewHas('records', fn ($rows) => $rows->total() === 18 && $rows->count() === 15 && $rows->hasMorePages());
        $this->get(route('admin.directory.youth.index', ['identity' => 'verified']))->assertOk()->assertViewHas('records', fn ($rows) => $rows->total() === 1);
        $this->get(route('admin.directory.youth.index', ['identity' => 'unverified']))->assertOk()->assertViewHas('records', fn ($rows) => $rows->total() === 17);
        $this->get(route('admin.directory.youth.index', ['district' => $youth->primaryDomicile->administrativeArea->code]))->assertOk()->assertViewHas('records', fn ($rows) => $rows->total() === 1);
        $this->get(route('admin.directory.communities.index', ['category' => $org->category->slug, 'status' => 'approved', 'operational' => 'active']))->assertOk()->assertViewHas('records', fn ($rows) => $rows->total() === 1);
        $this->get(route('admin.directory.communities.index', ['q' => 'absent-community']))->assertOk()->assertSee('Tidak ada data yang sesuai')->assertViewHas('records', fn ($rows) => $rows->total() === 0);
        $this->get(route('admin.directory.youth.index', ['q' => '%']))->assertOk()->assertViewHas('records', fn ($rows) => $rows->total() === 0);
    }

    public function test_dashboards_share_real_counts_preserve_queue_counts_and_link_to_accessible_destinations(): void
    {
        [$org, $youth, $program] = $this->intakeFixture();
        $activity = $this->activity($org, $youth, $program);
        $activity->update(['review_status' => 'approved', 'publication_status' => 'published', 'published_at' => now()]);
        $ecosystem = app(AdminAnalyticsService::class)->ecosystem();
        $this->assertSame(1, $ecosystem['youth']);
        $this->assertSame(1, $ecosystem['communities']);
        $this->assertSame(1, $ecosystem['activities']);
        $this->assertSame(1, $ecosystem['programs']['running']);
        $queues = app(VerifierQueueSummaryService::class)->summarize();
        foreach (['admin', 'verifier'] as $workspace) {
            $actor = User::factory()->create()->assignRole($workspace);
            $page = $this->actingAs($actor)->get(route($workspace.'.dashboard'))->assertOk()->assertSee('Data Ekosistem');
            if ($workspace === 'verifier') {
                $page->assertViewHas('ecosystem', $ecosystem)->assertViewHas('queues', $queues)->assertSee('Gambaran ekosistem')->assertSee('Ringkasan antrean');
            }
            foreach (['youth', 'communities', 'activities', 'programs'] as $kind) {
                $page->assertSee(route($workspace.'.directory.'.$kind.'.index'), false);
                $this->get(route($workspace.'.directory.'.$kind.'.index'))->assertOk();
            }
        }
        $this->assertSame($queues, app(VerifierQueueSummaryService::class)->summarize());
    }

    public function test_directory_query_count_does_not_grow_per_youth(): void
    {
        $admin = User::factory()->create()->assignRole('admin');
        $this->intakeFixture();
        $service = app(GovernmentDirectoryService::class);
        DB::enableQueryLog();
        $service->listing($admin, 'admin', 'youth', []);
        $one = count(DB::getQueryLog());
        DB::disableQueryLog();
        User::factory()->count(14)->create()->each(fn ($u) => $this->completeYouthOnboarding($u->assignRole('youth')));
        DB::flushQueryLog();
        DB::enableQueryLog();
        $service->listing($admin, 'admin', 'youth', []);
        $many = count(DB::getQueryLog());
        DB::disableQueryLog();
        $this->assertLessThanOrEqual($one + 3, $many);
    }

    public function test_village_domicile_is_filtered_and_projected_at_district_level_only(): void
    {
        [$org, $youth] = $this->intakeFixture();
        $district = $youth->primaryDomicile->administrativeArea;
        $village = AdministrativeArea::create(['code' => 'GOV-TEST-VILLAGE', 'name' => 'Private Village Detail', 'area_level' => 'village', 'parent_id' => $district->getKey()]);
        $youth->primaryDomicile()->update(['administrative_area_id' => $village->getKey()]);
        $admin = User::factory()->create()->assignRole('admin');
        $this->actingAs($admin)->get(route('admin.directory.youth.index', ['district' => $district->code]))->assertOk()->assertViewHas('records', fn ($rows) => $rows->total() === 1 && $rows->first()['cells'][0] === $district->name)->assertDontSee('Private Village Detail');
    }

    private function activity($org, $youth, $program): Activity
    {
        $category = ActivityCategory::create(['name' => 'Directory Category', 'slug' => 'directory-category']);

        return Activity::create(['organization_id' => $org->getKey(), 'created_by_user_id' => $youth->getKey(), 'program_id' => $program->getKey(), 'category_id' => $category->getKey(), 'title' => 'Directory Activity', 'slug' => 'directory-activity', 'description' => 'Activity data', 'location_type' => 'offline', 'start_at' => now()->addDay(), 'end_at' => now()->addDays(2), 'registration_mode' => 'open', 'review_status' => 'draft', 'publication_status' => 'unpublished', 'execution_status' => 'scheduled']);
    }
}
