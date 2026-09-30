<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\FinancialItem;
use App\Models\FinancialReport;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationMembership;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\ProgramLogbook;
use App\Models\ProgramProposal;
use App\Models\User;
use App\Services\FinancialReportTotalsService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FinancialReportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        Storage::fake('financial_receipts');
    }

    public function test_eligible_manager_can_create_draft_but_ineligible_and_other_users_cannot(): void
    {
        [$organization, $manager, $program] = $this->context('create');
        $this->actingAs($manager)->get(route('manager.financial-reports.create', [$organization, $program]))->assertOk()->assertSee('Mulai E-LPJ');
        $this->actingAs($manager)->post(route('manager.financial-reports.store', [$organization, $program]), ['notes' => 'Catatan'])->assertRedirect();
        $report = FinancialReport::firstOrFail();
        $this->assertSame(FinancialReport::STATUS_DRAFT, $report->status);
        $this->assertSame(1, $report->version);
        $this->actingAs($manager)->post(route('manager.financial-reports.store', [$organization, $program]))->assertForbidden();
        $this->actingAs($this->roleUser('youth'))->get(route('manager.financial-reports.show', [$organization, $program, $report]))->assertForbidden();
        $this->actingAs($this->roleUser('verifier'))->get(route('verifier.financial-reports.show', $report))->assertForbidden();
    }

    public function test_manager_crud_items_uses_exact_decimal_totals_and_private_receipts(): void
    {
        [$organization, $manager, $program] = $this->context('items');
        $report = $this->report($program);
        $this->actingAs($manager)->post(route('manager.financial-reports.items.store', [$organization, $program, $report]), $this->itemData('0.10', ['receipt' => UploadedFile::fake()->create('receipt.pdf', 100, 'application/pdf')]))->assertRedirect();
        $this->actingAs($manager)->post(route('manager.financial-reports.items.store', [$organization, $program, $report]), $this->itemData('0.20'))->assertRedirect();
        $income = FinancialItem::create(['financial_report_id' => $report->getKey(), 'transaction_type' => FinancialItem::TYPE_INCOME, 'transaction_date' => now()->toDateString(), 'description' => 'Dukungan', 'amount' => '9999999999999999.99']);
        $summary = app(FinancialReportTotalsService::class)->summarize($report->fresh('items'));
        $this->assertSame('0.30', $summary['realization_total']);
        $this->assertSame('9999999999999999.99', $summary['income_total']);
        $item = FinancialItem::query()->whereNotNull('receipt_path')->firstOrFail();
        Storage::disk('financial_receipts')->assertExists($item->receipt_path);
        $this->assertArrayNotHasKey('receipt_path', $item->toArray());
        $this->actingAs($manager)->get(route('manager.financial-reports.items.receipt', [$organization, $program, $report, $item]))->assertOk()->assertHeader('Cache-Control', 'max-age=0, no-store, private');
        $this->actingAs($manager)->delete(route('manager.financial-reports.items.destroy', [$organization, $program, $report, $income]))->assertRedirect();
        $this->assertSoftDeleted($income);
    }

    public function test_item_validation_and_nested_idor_are_enforced(): void
    {
        [$organization, $manager, $program] = $this->context('validation');
        $report = $this->report($program);
        $this->actingAs($manager)->post(route('manager.financial-reports.items.store', [$organization, $program, $report]), $this->itemData('0'))->assertSessionHasErrors('amount');
        $this->actingAs($manager)->post(route('manager.financial-reports.items.store', [$organization, $program, $report]), $this->itemData('100', ['receipt' => UploadedFile::fake()->create('script.exe', 10, 'application/octet-stream')]))->assertSessionHasErrors('receipt');
        [, $otherManager, $otherProgram] = $this->context('other');
        $otherReport = $this->report($otherProgram);
        $item = FinancialItem::create(array_merge($this->itemData('100'), ['financial_report_id' => $otherReport->getKey()]));
        $this->actingAs($manager)->patch(route('manager.financial-reports.items.update', [$organization, $program, $report, $item]), $this->itemData('200'))->assertNotFound();
        $this->actingAs($otherManager)->get(route('manager.financial-reports.show', [$organization, $program, $report]))->assertForbidden();
    }

    public function test_submit_requires_items_and_locks_submitted_report(): void
    {
        [$organization, $manager, $program] = $this->context('submit');
        $report = $this->report($program);
        $this->actingAs($manager)->post(route('manager.financial-reports.submit', [$organization, $program, $report]))->assertSessionHasErrors('report');
        FinancialItem::create(array_merge($this->itemData('250000.25'), ['financial_report_id' => $report->getKey()]));
        $this->actingAs($manager)->post(route('manager.financial-reports.submit', [$organization, $program, $report]))->assertRedirect();
        $this->assertSame(FinancialReport::STATUS_SUBMITTED, $report->fresh()->status);
        $this->actingAs($manager)->get(route('manager.financial-reports.edit', [$organization, $program, $report]))->assertForbidden();
        $this->actingAs($manager)->post(route('manager.financial-reports.items.store', [$organization, $program, $report]), $this->itemData('100'))->assertForbidden();
    }

    public function test_verifier_queue_review_notifications_and_program_domain_separation(): void
    {
        [$organization, $manager, $program] = $this->context('review');
        $report = $this->report($program, FinancialReport::STATUS_SUBMITTED);
        FinancialItem::create(array_merge($this->itemData('1000'), ['financial_report_id' => $report->getKey()]));
        $verifier = $this->roleUser('verifier');
        $this->get(route('verifier.financial-reports.index'))->assertRedirect(route('login'));
        $this->actingAs($this->roleUser('admin'))->get(route('verifier.financial-reports.index'))->assertForbidden();
        $this->actingAs($verifier)->get(route('verifier.financial-reports.index'))->assertOk()->assertSee($program->title);
        $this->actingAs($verifier)->get(route('verifier.financial-reports.show', $report))->assertOk()->assertSee('Rincian transaksi')->assertSee('Ringkasan pelaksanaan');
        $this->actingAs($manager)->get(route('verifier.financial-reports.index'))->assertForbidden();
        $this->actingAs($verifier)->post(route('verifier.financial-reports.review', $report), ['decision' => 'revision'])->assertSessionHasErrors('notes');
        $this->actingAs($verifier)->post(route('verifier.financial-reports.review', $report), ['decision' => 'approved'])->assertRedirect();
        $this->assertSame(FinancialReport::STATUS_APPROVED, $report->fresh()->status);
        $this->assertSame(Program::STATUS_RUNNING, $program->fresh()->execution_status);
        $this->assertDatabaseHas('notifications', ['user_id' => $manager->getKey(), 'notification_type' => 'financial_report_reviewed']);
        $this->assertDatabaseHas('activity_log', ['event' => 'financial_report_approved']);
        $this->actingAs($verifier)->post(route('verifier.financial-reports.review', $report), ['decision' => 'rejected', 'notes' => 'Tidak valid'])->assertForbidden();
    }

    public function test_verifier_can_request_revision_or_reject_with_safe_notes_and_conflict_is_blocked(): void
    {
        [$organization, $manager, $program] = $this->context('decisions');
        $revision = $this->report($program, FinancialReport::STATUS_SUBMITTED);
        $verifier = $this->roleUser('verifier');
        $this->actingAs($verifier)->post(route('verifier.financial-reports.review', $revision), ['decision' => 'revision', 'notes' => 'Lengkapi bukti transaksi.'])->assertRedirect();
        $this->assertSame(FinancialReport::STATUS_REVISION, $revision->fresh()->status);

        [, , $otherProgram] = $this->context('rejection');
        $rejected = $this->report($otherProgram, FinancialReport::STATUS_SUBMITTED);
        $this->actingAs($verifier)->post(route('verifier.financial-reports.review', $rejected), ['decision' => 'rejected', 'notes' => 'Dokumen tidak sesuai.'])->assertRedirect();
        $this->assertSame(FinancialReport::STATUS_REJECTED, $rejected->fresh()->status);
        $payloads = User::query()->whereKey($manager->getKey())->firstOrFail()->siporaNotifications()->pluck('data')->all();
        $this->assertStringNotContainsString('receipt_path', json_encode($payloads, JSON_THROW_ON_ERROR));

        $manager->assignRole('verifier');
        $conflicted = FinancialReport::create(['program_id' => $program->getKey(), 'version' => 2, 'status' => FinancialReport::STATUS_SUBMITTED, 'submitted_at' => now()]);
        $this->actingAs($manager)->post(route('verifier.financial-reports.review', $conflicted), ['decision' => 'approved'])->assertForbidden();
        $this->assertSame(FinancialReport::STATUS_SUBMITTED, $conflicted->fresh()->status);
        $this->assertSame($organization->getKey(), $program->organization_id);
    }

    public function test_revision_creates_new_version_and_copies_items_and_private_evidence(): void
    {
        [$organization, $manager, $program] = $this->context('revision');
        $source = $this->report($program, FinancialReport::STATUS_REVISION);
        Storage::disk('financial_receipts')->put('reports/source/evidence.pdf', 'pdf');
        FinancialItem::create(array_merge($this->itemData('500.50'), ['financial_report_id' => $source->getKey(), 'receipt_path' => 'reports/source/evidence.pdf']));
        $this->actingAs($manager)->post(route('manager.financial-reports.revise', [$organization, $program, $source]))->assertRedirect();
        $revision = FinancialReport::query()->where('version', 2)->firstOrFail();
        $this->assertSame(FinancialReport::STATUS_DRAFT, $revision->status);
        $this->assertSame('500.50', $revision->items()->firstOrFail()->amount);
        $this->assertNotSame($source->items()->firstOrFail()->receipt_path, $revision->items()->firstOrFail()->receipt_path);
        Storage::disk('financial_receipts')->assertExists($revision->items()->firstOrFail()->receipt_path);
    }

    public function test_phase_boundary_and_uuid_schema_are_correct(): void
    {
        $this->assertTrue(Schema::hasTable('financial_reports'));
        $this->assertTrue(Schema::hasTable('financial_items'));
        $this->assertTrue(Schema::hasTable('program_evaluations'));
        [, , $program] = $this->context('uuid');
        $report = $this->report($program);
        $this->assertSame(16, strlen($report->getKey()));
    }

    private function context(string $suffix): array
    {
        $manager = $this->roleUser('youth');
        $organizationCategory = OrganizationCategory::create(['name' => 'Org '.$suffix, 'slug' => 'org-'.$suffix]);
        $organization = Organization::create(['created_by_user_id' => $manager->getKey(), 'category_id' => $organizationCategory->getKey(), 'name' => 'Komunitas '.$suffix, 'slug' => 'community-'.$suffix, 'description' => 'Aktif', 'social_links' => (object) [], 'review_status' => Organization::REVIEW_APPROVED, 'operational_status' => Organization::OPERATIONAL_ACTIVE, 'approved_at' => now()]);
        OrganizationMembership::create(['organization_id' => $organization->getKey(), 'user_id' => $manager->getKey(), 'access_role' => OrganizationMembership::ROLE_LEADER, 'membership_status' => OrganizationMembership::STATUS_ACTIVE, 'requested_at' => now(), 'approved_at' => now(), 'approved_by' => $manager->getKey()]);
        $category = ProgramCategory::create(['name' => 'Program '.$suffix, 'slug' => 'program-'.$suffix]);
        $program = Program::create(['organization_id' => $organization->getKey(), 'category_id' => $category->getKey(), 'created_by_user_id' => $manager->getKey(), 'title' => 'Program '.$suffix, 'slug' => 'program-'.$suffix, 'execution_status' => Program::STATUS_RUNNING]);
        $activityCategory = ActivityCategory::firstOrCreate(['slug' => 'activity-'.$suffix], ['name' => 'Activity '.$suffix]);
        $activity = Activity::create(['organization_id' => $organization->getKey(), 'program_id' => $program->getKey(), 'category_id' => $activityCategory->getKey(), 'created_by_user_id' => $manager->getKey(), 'title' => 'Activity '.$suffix, 'slug' => 'activity-'.$suffix, 'location_type' => 'offline', 'start_at' => now()->addDay(), 'end_at' => now()->addDay()->addHour(), 'registration_mode' => 'open', 'review_status' => Activity::REVIEW_APPROVED, 'publication_status' => Activity::PUBLICATION_UNPUBLISHED, 'execution_status' => Activity::EXECUTION_SCHEDULED]);
        ProgramProposal::create(['program_id' => $program->getKey(), 'version' => 1, 'status' => ProgramProposal::STATUS_APPROVED, 'requested_budget' => '1000000.00', 'submitted_at' => now()->subDays(2), 'reviewed_at' => now()->subDay()]);
        ProgramLogbook::create(['program_id' => $program->getKey(), 'activity_id' => $activity->getKey(), 'created_by_user_id' => $manager->getKey(), 'log_date' => now()->toDateString(), 'summary' => 'Pelaksanaan', 'progress_percent' => 50, 'status' => ProgramLogbook::STATUS_APPROVED, 'submitted_at' => now()->subDay(), 'reviewed_at' => now()]);

        return [$organization, $manager, $program];
    }

    private function report(Program $program, string $status = FinancialReport::STATUS_DRAFT): FinancialReport
    {
        return FinancialReport::create(['program_id' => $program->getKey(), 'version' => 1, 'status' => $status, 'submitted_at' => $status === FinancialReport::STATUS_DRAFT ? null : now()]);
    }

    private function itemData(string $amount, array $override = []): array
    {
        return array_merge(['transaction_type' => FinancialItem::TYPE_EXPENSE, 'transaction_date' => now()->toDateString(), 'description' => 'Transaksi kegiatan', 'amount' => $amount], $override);
    }

    private function roleUser(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }
}
