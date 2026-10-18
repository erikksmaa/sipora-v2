<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityAttendance;
use App\Models\ActivityParticipation;
use App\Models\ActivitySession;
use App\Models\AuditEntry;
use App\Models\Program;
use App\Models\ProgramApplication;
use App\Models\ProgramProposal;
use App\Models\User;
use App\Models\UserCertificate;
use App\Models\UserNotification;
use App\Services\Activity\ActivityPassportService;
use App\Services\Discovery\ProgramDiscoveryService;
use App\Services\Program\ProgramIntakeService;
use App\Services\Youth\YouthOnboardingService;
use App\Support\Nik;
use Database\Seeders\CompetitionDemoSeeder;
use Database\Seeders\DemoYouthSeeder;
use Database\Seeders\GovernmentProgramJourneySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class GovernmentProgramJourneySeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_government_reviewed_academy_is_connected_current_usable_and_idempotent(): void
    {
        $this->travelTo(Carbon::parse('2026-10-18 09:00:00'));
        $this->seed(CompetitionDemoSeeder::class);
        $this->assertSame('2026-10-18 09:00:00', now()->toDateTimeString());
        $program = Program::where('slug', GovernmentProgramJourneySeeder::PROGRAM_SLUG)->firstOrFail();
        $activity = Activity::where('slug', GovernmentProgramJourneySeeder::ACTIVITY_SLUG)->firstOrFail();
        $erik = User::where('email', 'youth1@sipora.test')->firstOrFail();
        $manager = $program->creator;
        $verifier = User::where('email', 'verifier@sipora.test')->firstOrFail();
        $this->assertSame('running', $program->execution_status);
        $this->assertTrue(app(ProgramDiscoveryService::class)->isPublic($program));
        $this->assertSame('review', $program->selection_mode);
        $this->assertTrue($program->registration_open_at->isPast());
        $this->assertTrue($program->registration_close_at->isFuture());
        $summary = app(ProgramIntakeService::class)->summary($program);
        $this->assertSame(30, $summary['quota']);
        $this->assertSame(16, $summary['total']);
        $this->assertSame(24, $summary['remaining']);
        $this->assertSame(['submitted' => 3, 'under_review' => 2, 'accepted' => 6, 'waitlisted' => 2, 'rejected' => 2, 'withdrawn' => 1], $summary['counts']);
        $this->assertSame(16, $program->applications()->distinct()->count('user_id'));
        $this->assertSame($verifier->getKey(), $program->applications()->where('user_id', $erik->getKey())->first()->reviewed_by);
        $this->assertSame($program->getKey(), $activity->program_id);
        $this->assertSame($program->organization_id, $activity->organization_id);
        $this->assertSame(4, $activity->sessions()->count());
        $this->assertSame('completed', $activity->execution_status);
        $this->assertTrue($activity->end_at->isPast());
        $participant = $activity->participations()->where('user_id', $erik->getKey())->firstOrFail();
        $this->assertSame('accepted', $participant->registration_status);
        $this->assertSame('completed', $participant->completion_status);
        $entry = app(ActivityPassportService::class)->queryFor($erik)->whereKey($participant->getKey())->firstOrFail();
        $this->assertSame(4, $entry->present_count);
        $this->assertSame(0, $entry->absent_count);
        $this->assertNotNull($entry->certificate);
        $this->assertSame($participant->getKey(), $entry->certificate->participation_id);
        $this->assertSame($erik->getKey(), $entry->certificate->user_id);
        foreach ($activity->sessions as $session) {
            $this->assertSame(6, $session->attendances()->count());
            $this->assertTrue($session->end_at->isPast());
            foreach ($session->attendances as $attendance) {
                if ($attendance->attendance_status !== 'present') {
                    continue;
                }
                $this->assertTrue($attendance->checked_in_at->between($session->start_at, $session->end_at));
                $this->assertTrue($attendance->checked_out_at->between($attendance->checked_in_at, $session->end_at));
            }
        }
        foreach (DemoYouthSeeder::users() as $user) {
            $this->assertTrue(Nik::validFormat($user->identity->nationalId()));
            $this->assertStringStartsWith('000000', $user->identity->nationalId());
            $this->assertSame($user->profile->birth_date->toDateString(), Nik::birthDate($user->identity->nationalId()));
            $this->assertNotEmpty($user->profile->birth_place);
            $this->assertContains($user->profile->gender, ['male', 'female']);
            $this->assertSame($user->primaryDomicile->administrative_area_id, $user->primaryDomicile->village->parent_id);
            $this->assertGreaterThanOrEqual(1, $user->interests()->count());
            $this->assertLessThanOrEqual(5, $user->interests()->count());
        }
        $this->assertTrue(app(YouthOnboardingService::class)->state($erik)['onboardingComplete']);
        $this->actingAs($erik)->get(route('programs.show', $program))->assertOk()->assertSee('Diterima')->assertSee($activity->title);
        $this->get(route('activities.show', $activity))->assertOk()->assertSee($program->title);
        $this->get(route('youth.passport.show', $participant))->assertOk()->assertSee($activity->title);
        $this->get(route('youth.certificates.show', $entry->certificate))->assertOk()->assertSee($activity->title);
        $this->get(route('youth.certificates.download', $entry->certificate))->assertOk()->assertHeader('Content-Type', 'application/pdf');
        $this->actingAs($manager)->get(route('manager.programs.applications.index', [$program->organization, $program]).'?q=Erik')->assertOk()->assertSee('Erik Kusuma Rais');
        $this->get(route('manager.activities.participants.index', [$activity->organization, $activity]))->assertOk()->assertSee('Erik Kusuma Rais');
        $this->get(route('manager.activities.attendance.index', [$activity->organization, $activity, $activity->sessions->first()]))->assertOk()->assertSee('Erik Kusuma Rais');
        $this->actingAs($verifier)->get(route('verifier.programs.applications.index', $program))->assertOk()->assertSee($program->title);
        $admin = User::where('email', 'admin@sipora.test')->firstOrFail();
        $this->actingAs($admin)->get(route('admin.directory.programs.show', $program))->assertOk()->assertSee($program->title);
        $models = [Program::class, ProgramProposal::class, ProgramApplication::class, Activity::class, ActivitySession::class, ActivityParticipation::class, ActivityAttendance::class, UserCertificate::class, UserNotification::class, AuditEntry::class];
        $counts = array_map(fn ($model) => $model::count(), $models);
        $certificate = $entry->certificate->only(['id', 'certificate_number', 'verification_code', 'issued_at']);
        $this->seed(CompetitionDemoSeeder::class);
        $this->assertSame($counts, array_map(fn ($model) => $model::count(), $models));
        $this->assertEquals($certificate, $entry->certificate->fresh()->only(array_keys($certificate)));
        $this->assertSame(24, app(ProgramIntakeService::class)->summary($program->fresh())['remaining']);
        $this->travelBack();
    }

    public function test_golden_journey_refuses_production_before_any_write(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        $this->expectException(\RuntimeException::class);
        (new GovernmentProgramJourneySeeder)->run();
    }
}
