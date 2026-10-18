<?php

namespace Database\Seeders;

use App\Actions\Manager\CompleteActivityExecutionAction;
use App\Actions\Manager\CompleteActivityParticipationAction;
use App\Actions\Manager\IssueActivityCertificateAction;
use App\Actions\Manager\PublishActivityAction;
use App\Actions\Manager\RecordActivityAttendanceAction;
use App\Actions\Manager\ReviewActivityRegistrationAction;
use App\Actions\Manager\StartProgramExecutionAction;
use App\Actions\Manager\SubmitActivityForReviewAction;
use App\Actions\Manager\SubmitProgramProposalAction;
use App\Actions\Verifier\ReviewActivityAction;
use App\Actions\Verifier\ReviewProgramProposalAction;
use App\Actions\Youth\RegisterForActivityAction;
use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\ActivitySession;
use App\Models\Organization;
use App\Models\OrganizationMembership;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\User;
use App\Models\UserCertificate;
use App\Services\Program\ProgramIntakeService;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

final class GovernmentProgramJourneySeeder extends Seeder
{
    public const PROGRAM_SLUG = 'pemalang-digital-youth-academy-2026';

    public const ACTIVITY_SLUG = 'academy-workshop-full-stack-web-development-untuk-pemuda';

    public const STATUSES = ['accepted', 'accepted', 'accepted', 'accepted', 'accepted', 'accepted', 'submitted', 'submitted', 'submitted', 'under_review', 'under_review', 'waitlisted', 'waitlisted', 'rejected', 'rejected', 'withdrawn'];

    public function run(): void
    {
        if (! ((app()->environment('local') && DB::connection()->getDatabaseName() === 'sipora')
            || (app()->environment('testing') && DB::connection()->getDatabaseName() === 'sipora_regression_test'))) {
            throw new RuntimeException('Golden journey hanya untuk local sipora atau testing.');
        }
        DB::transaction(function (): void {
            $this->call(DemoYouthOnboardingSeeder::class);
            $community = Organization::where('slug', 'komunitas-pemuda-digital-pemalang')->where('review_status', 'approved')->where('operational_status', 'active')->firstOrFail();
            $manager = OrganizationMembership::where('organization_id', $community->getKey())->where('membership_status', 'active')->where('access_role', 'manager')->firstOrFail()->user;
            $verifier = User::where('email', 'verifier@sipora.test')->firstOrFail();
            $users = DemoYouthSeeder::users()->take(16);
            if ($users->count() !== 16 || $users->first()->email !== 'youth1@sipora.test') {
                throw new RuntimeException('Jalankan CompetitionDemoSeeder untuk menyiapkan akun kanonis.');
            }
            $oldActivity = Activity::where('slug', self::ACTIVITY_SLUG)->first();
            // Keep the completed batch chronology and issued certificates stable on reruns.
            $start = $oldActivity ? CarbonImmutable::parse($oldActivity->start_at) : CarbonImmutable::today(config('sipora.display_timezone'))->subDays(35)->setTime(9, 0)->utc();
            $end = $start->addDays(3)->addHours(6);
            $program = Program::firstOrCreate(['slug' => self::PROGRAM_SLUG], [
                'organization_id' => $community->getKey(), 'created_by_user_id' => $manager->getKey(),
                'category_id' => ProgramCategory::where('slug', 'pengembangan-kapasitas-pemuda')->firstOrFail()->getKey(),
                'title' => 'Pemalang Digital Youth Academy 2026', 'execution_status' => 'planned',
                'description' => 'Program pengembangan keterampilan digital pemuda Kabupaten Pemalang melalui rangkaian kegiatan pelatihan dan praktik berbasis proyek. Dindikpora melakukan pengawasan dan seleksi bersama komunitas pelaksana. Gelombang pertama selesai; pendaftaran gelombang berikutnya tetap dibuka. DATA DEMO SIPORA.',
                'objectives' => 'Membangun keterampilan pengembangan web, kolaborasi tim, serta portofolio proyek pemuda Pemalang.',
                'start_date' => $start->subDays(25)->toDateString(), 'end_date' => now()->addDays(90)->toDateString(),
            ]);
            $activity = Activity::firstOrCreate(['slug' => self::ACTIVITY_SLUG], [
                'organization_id' => $community->getKey(), 'program_id' => $program->getKey(), 'created_by_user_id' => $manager->getKey(),
                'category_id' => ActivityCategory::where('slug', 'teknologi')->firstOrFail()->getKey(),
                'title' => 'Workshop Full-Stack Web Development untuk Pemuda',
                'description' => 'Gelombang pertama Academy: membangun aplikasi layanan komunitas dengan Laravel dan antarmuka web, dari perancangan sampai presentasi proyek. DATA DEMO SIPORA.',
                'location_type' => 'offline', 'venue_name' => 'Ruang Belajar Komunitas Pemuda Digital Pemalang',
                'administrative_area_id' => $community->administrative_area_id, 'address_text' => 'Lokasi fiktif tingkat kecamatan, Kabupaten Pemalang',
                'start_at' => $start, 'end_at' => $end, 'registration_open_at' => $start->subDays(25), 'registration_close_at' => $start->subDay(),
                'quota' => 30, 'registration_mode' => 'approval_required', 'min_age' => 16, 'max_age' => 30,
                'requires_identity_verification' => true, 'members_only' => false, 'certificate_enabled' => true,
                'review_status' => 'draft', 'publication_status' => 'unpublished', 'execution_status' => 'scheduled',
            ]);
            if ($program->organization_id !== $community->getKey() || $activity->program_id !== $program->getKey() || $activity->organization_id !== $community->getKey()) {
                throw new RuntimeException('Slug Academy bertabrakan dengan pemilik fixture lain.');
            }
            $poster = Activity::where('slug', 'workshop-web-development-untuk-pemuda')->value('poster_path');
            if (! $activity->poster_path && $poster && Storage::disk('public')->exists($poster)) {
                $activity->update(['poster_path' => $poster]);
            }
            $path = 'proposals/demo-academy-2026.pdf';
            if (! Storage::disk('proposal_documents')->exists($path)) {
                Storage::disk('proposal_documents')->put($path, file_get_contents(database_path('fixtures/demo/proposal.pdf')));
            }
            $proposal = $program->proposals()->firstOrCreate(['version' => 1], ['status' => 'draft', 'requested_budget' => 30000000, 'proposal_document_path' => $path]);
            $previousClock = Carbon::getTestNow();
            try {
                Carbon::setTestNow($start->subDays(20));
                if ($proposal->status === 'draft') {
                    $proposal = app(SubmitProgramProposalAction::class)->execute($manager, $proposal);
                }
                if (in_array($proposal->status, ['submitted', 'under_review'])) {
                    app(ReviewProgramProposalAction::class)->execute($verifier, $proposal, 'approved', 'Proposal fiktif Academy disetujui untuk demonstrasi alur pemerintah dan komunitas.');
                }
                if ($program->execution_status === 'planned') {
                    $program = app(StartProgramExecutionAction::class)->execute($manager, $program);
                }
                if ($activity->review_status === 'draft') {
                    $activity = app(SubmitActivityForReviewAction::class)->execute($manager, $activity);
                }
                if ($activity->review_status === 'pending_review') {
                    $activity = app(ReviewActivityAction::class)->execute($verifier, $activity, 'approved', 'Workshop fiktif layak dipublikasikan.');
                }
                if ($activity->publication_status === 'unpublished') {
                    $activity = app(PublishActivityAction::class)->execute($manager, $activity);
                }
                $intake = app(ProgramIntakeService::class);
                // No reset of decisions, registrations or certificates on a rerun.
                if (! $program->registration_enabled) {
                    $program = $intake->configure($manager, $program, ['registration_enabled' => true, 'selection_mode' => 'review', 'quota' => 30, 'registration_open_at' => $start->subDays(25)->timezone(config('sipora.display_timezone'))->toDateTimeString(), 'registration_close_at' => $start->addDays(90)->timezone(config('sipora.display_timezone'))->toDateTimeString()]);
                }
                Carbon::setTestNow($start->subDays(14));
                foreach ($users as $index => $youth) {
                    $application = $program->applications()->where('user_id', $youth->getKey())->first();
                    if (! $application) {
                        $application = $intake->apply($youth, $program);
                        $target = self::STATUSES[$index];
                        if ($target === 'withdrawn') {
                            $intake->withdraw($youth, $program);
                        } elseif ($target !== 'submitted') {
                            $intake->review($verifier, $program, $application, $target, 'submitted', 'Keputusan seleksi fiktif untuk demonstrasi Academy.');
                        }
                    }
                    if ($index >= 6) {
                        continue;
                    }
                    $participant = $activity->participations()->where('user_id', $youth->getKey())->first();
                    if (! $participant) {
                        $participant = app(RegisterForActivityAction::class)->execute($youth->fresh(), $activity, 'Peserta gelombang pertama Academy.');
                        app(ReviewActivityRegistrationAction::class)->execute($manager, $activity, $participant, 'accepted', null);
                    }
                }
                foreach (['Fundamental Web Development', 'Backend dengan Laravel', 'Frontend & Integrasi', 'Final Project dan Evaluasi'] as $index => $title) {
                    $session = ActivitySession::firstOrCreate(['activity_id' => $activity->getKey(), 'session_number' => $index + 1], [
                        'title' => $title, 'description' => 'Pelatihan dan praktik proyek Academy gelombang pertama.',
                        'start_at' => $start->addDays($index), 'end_at' => $start->addDays($index)->addHours(6), 'venue_name' => $activity->venue_name,
                    ]);
                    Carbon::setTestNow($session->end_at->copy()->addMinutes(10));
                    foreach ($activity->participations()->where('registration_status', 'accepted')->orderBy('requested_at')->orderBy('id')->get() as $participant) {
                        if ($session->attendances()->where('participation_id', $participant->getKey())->exists()) {
                            continue;
                        }
                        $absent = $participant->user_id === $users[5]->getKey() && $index === 2;
                        app(RecordActivityAttendanceAction::class)->execute($manager, $activity, $session, $participant, [
                            'attendance_status' => $absent ? 'excused' : 'present',
                            'checked_in_at' => $absent ? null : $session->start_at->copy()->addMinutes(5),
                            'checked_out_at' => $absent ? null : $session->end_at->copy()->subMinutes(5),
                            'notes' => $absent ? 'Izin keluarga; ditinjau pengelola.' : null,
                        ]);
                    }
                }
                Carbon::setTestNow($end->addHour());
                if ($activity->execution_status !== 'completed') {
                    $activity = app(CompleteActivityExecutionAction::class)->execute($manager, $activity);
                }
                foreach ($activity->participations()->where('registration_status', 'accepted')->get() as $participant) {
                    if ($participant->completion_status === 'pending') {
                        $participant = app(CompleteActivityParticipationAction::class)->execute($manager, $activity, $participant, 'completed');
                    }
                    if (! UserCertificate::withTrashed()->where('participation_id', $participant->getKey())->exists()) {
                        app(IssueActivityCertificateAction::class)->execute($manager, $activity, $participant);
                    }
                }
            } finally {
                Carbon::setTestNow($previousClock);
            }
            // Extend the still-running academy intake for the next batch without moving past sessions.
            if ($program->registration_close_at->isPast()) {
                $program->update(['registration_close_at' => now()->addDays(30), 'end_date' => now()->addDays(90)->toDateString()]);
            }
        });
        $this->command?->info('Academy demo ready: 30 quota; 16 fixture applications; completed workshop and issued certificates.');
    }
}
