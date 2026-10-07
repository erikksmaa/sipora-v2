<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\FinancialItem;
use App\Models\Organization;
use App\Models\User;
use App\Models\UserProfile;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\MediaFixtureSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

// Non-destructive acceptance checks against the documented local demo dataset.
class MediaEnrichmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // These acceptance checks require the documented demo records even when
        // a preceding RefreshDatabase test has recreated the test database.
        foreach (['community_media', 'activity_media', 'financial_receipts', 'public'] as $disk) {
            Storage::fake($disk);
        }
        $this->seed(DatabaseSeeder::class);
    }

    public function test_public_surfaces_render_without_private_evidence_paths(): void
    {
        foreach (['/', '/communities', '/activities', '/opportunities', '/programs', '/youth', '/about', '/contact', '/portfolio/erik-kusuma-rais'] as $path) {
            $this->get($path)->assertOk()->assertDontSee('financial-receipts')->assertDontSee('bukti-transaksi');
        }
    }

    public function test_public_media_respects_publication_and_photo_visibility(): void
    {
        $organization = Organization::where('slug', 'komunitas-programmer-pemalang')->firstOrFail();
        $this->get(route('communities.logo', $organization))->assertOk();
        $organization->update(['review_status' => 'pending_review', 'operational_status' => 'inactive']);
        $this->get(route('communities.logo', $organization))->assertNotFound();
        $organization->update(['review_status' => 'approved', 'operational_status' => 'active']);

        $activity = Activity::whereNotNull('poster_path')->where('review_status', 'approved')->where('publication_status', 'published')
            ->where('organization_id', $organization->getKey())->firstOrFail();
        $this->get(route('activities.poster', $activity))->assertOk();
        $activity->update(['publication_status' => Activity::PUBLICATION_UNPUBLISHED]);
        $this->get(route('activities.poster', $activity))->assertNotFound();

        $profile = UserProfile::where('public_slug', 'erik-kusuma-rais')->firstOrFail();
        $this->get(route('portfolio.photo', $profile->public_slug))->assertOk();
        $profile->user->profileVisibility->update(['show_photo' => false]);
        $this->get(route('portfolio.photo', $profile->public_slug))->assertNotFound();
        $this->get(route('portfolio.show', $profile->public_slug))->assertOk()->assertDontSee(route('portfolio.photo', $profile->public_slug), false);
    }

    public function test_private_receipts_are_renderable_only_by_authorized_roles(): void
    {
        $item = FinancialItem::where('receipt_path', 'like', 'phase23c/%')->whereHas('report', fn ($q) => $q->where('status', 'submitted'))->firstOrFail();
        $report = $item->report;
        $program = $report->program;
        $managerUrl = route('manager.financial-reports.items.receipt', [$program->organization, $program, $report, $item]);
        $verifierUrl = route('verifier.financial-reports.items.receipt', [$report, $item]);

        $this->get($managerUrl)->assertRedirect(route('login'));
        $this->get($verifierUrl)->assertRedirect(route('login'));
        $this->actingAs(User::where('email', 'youth1@sipora.test')->firstOrFail());
        $this->get($managerUrl)->assertOk()->assertHeader('Content-Type', 'image/png');
        $this->get(route('manager.financial-reports.show', [$program->organization, $program, $report]))->assertOk()->assertSee('media-evidence')->assertDontSee($item->receipt_path);
        $this->actingAs(User::where('email', 'verifier@sipora.test')->firstOrFail());
        $response = $this->get($verifierUrl)->assertOk()->assertHeader('Content-Type', 'image/png');
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        $this->get(route('verifier.financial-reports.show', $report))->assertOk()->assertSee('media-evidence');
        $this->actingAs(User::factory()->create()->assignRole('youth'));
        $this->get($managerUrl)->assertRedirect(route('youth.onboarding'));
        $this->get($verifierUrl)->assertForbidden();
    }

    public function test_seeded_files_exist_and_seeding_is_repeatable_without_changing_visibility(): void
    {
        foreach (['community_media', 'activity_media', 'financial_receipts', 'public'] as $disk) {
            Storage::fake($disk);
        }
        $before = User::where('email', 'youth1@sipora.test')->firstOrFail()->profileVisibility->getAttributes();
        $this->seed(MediaFixtureSeeder::class);
        $profile = UserProfile::where('public_slug', 'erik-kusuma-rais')->firstOrFail();
        $path = $profile->profile_photo_path;
        $hash = hash('sha256', Storage::disk('public')->get($path));
        $this->seed(MediaFixtureSeeder::class);
        $this->assertSame($path, $profile->fresh()->profile_photo_path);
        $this->assertSame($hash, hash('sha256', Storage::disk('public')->get($path)));
        $this->assertSame($before, $profile->user->profileVisibility->getAttributes());
        $this->assertNull(FinancialItem::where('description', 'Dukungan mitra')->firstOrFail()->receipt_path);
        foreach (Organization::where('logo_path', 'like', 'phase23c/%')->get() as $record) {
            Storage::disk('community_media')->assertExists($record->logo_path);
        }
        foreach (Activity::where('poster_path', 'like', 'phase23c/%')->get() as $record) {
            Storage::disk('activity_media')->assertExists($record->poster_path);
            $xml = new \DOMDocument;
            $this->assertTrue($xml->loadXML(Storage::disk('activity_media')->get($record->poster_path)));
        }
    }
}
