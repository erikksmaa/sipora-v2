<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivityAttendance;
use App\Models\ActivityCategory;
use App\Models\ActivityParticipation;
use App\Models\ActivityReview;
use App\Models\ActivitySession;
use App\Models\AdministrativeArea;
use App\Models\AuditEntry;
use App\Models\FinancialItem;
use App\Models\FinancialReport;
use App\Models\Interest;
use App\Models\Opportunity;
use App\Models\OpportunityBookmark;
use App\Models\OpportunityCategory;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationExperience;
use App\Models\OrganizationMembership;
use App\Models\OrganizationVerificationRequest;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\ProgramEvaluation;
use App\Models\ProgramLogbook;
use App\Models\ProgramLogbookMedia;
use App\Models\ProgramProposal;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserAddress;
use App\Models\UserCertificate;
use App\Models\UserEducation;
use App\Models\UserIdentity;
use App\Models\UserIdentityVerification;
use App\Models\UserInterest;
use App\Models\UserNotification;
use App\Models\UserProfile;
use App\Models\UserProfileVisibility;
use App\Models\UserSkill;
use App\Models\UserSocialAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Dense, deterministic local QA fixtures. This seeder intentionally targets
 * application-owned tables; framework runtime tables and locked role topology
 * are documented exclusions in docs/QA_TESTING_GUIDE.md.
 */
final class QualityAssuranceSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $this->seedMasterData();
            $this->seedYouthProfiles();
            $this->seedIdentityHistory();
            $this->seedCommunityCoverage();
            $this->seedActivityCoverage();
            $this->seedProgramCoverage();
            $this->seedOpportunityCoverage();
            $this->seedNotificationsAndAudit();
        });
    }

    private function seedMasterData(): void
    {
        foreach ([
            'Kesehatan Pemuda', 'Kerelawanan',
        ] as $name) {
            Interest::withTrashed()->updateOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'deleted_at' => null]);
        }

        foreach (['Kesehatan', 'Kerelawanan', 'Literasi', 'Pariwisata'] as $name) {
            ActivityCategory::withTrashed()->updateOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'description' => 'Kategori QA khusus pengembangan.', 'deleted_at' => null]);
        }

        foreach (['Komunitas Pendidikan', 'Komunitas Sosial', 'Komunitas Lingkungan', 'Komunitas Budaya', 'Komunitas Kesehatan', 'Komunitas Relawan'] as $name) {
            OrganizationCategory::withTrashed()->updateOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'description' => 'Kategori QA khusus pengembangan.', 'deleted_at' => null]);
        }

        foreach (['Pendidikan Nonformal', 'Kerelawanan Pemuda', 'Lingkungan Hidup', 'Budaya Lokal', 'Kesehatan Pemuda', 'Inovasi Sosial', 'Pariwisata Pemuda'] as $name) {
            ProgramCategory::withTrashed()->updateOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'description' => 'Kategori QA khusus pengembangan.', 'deleted_at' => null]);
        }

        foreach (['Pertukaran Pemuda', 'Hibah', 'Konferensi', 'Komunitas', 'Inkubasi'] as $name) {
            OpportunityCategory::withTrashed()->updateOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'description' => 'Kategori QA khusus pengembangan.', 'deleted_at' => null]);
        }
    }

    private function seedYouthProfiles(): void
    {
        $areas = AdministrativeArea::query()->where('area_level', 'district')->orderBy('code')->get();
        $interests = Interest::query()->orderBy('slug')->get();
        $skills = Skill::query()->orderBy('slug')->get();

        foreach ($this->youth() as $index => $user) {
            $number = $index + 1;
            $area = $areas[$index % $areas->count()];
            $profile = UserProfile::withTrashed()->firstOrNew(['user_id' => $user->getKey()]);
            if (! $profile->exists) {
                $profile->fill([
                    'public_slug' => Str::slug($user->name), 'full_name' => $user->name,
                    'birth_place' => 'Pemalang', 'birth_date' => now()->subYears(18 + ($index % 8))->subDays($index)->toDateString(),
                    'gender' => $index % 2 === 0 ? 'female' : 'male', 'phone' => sprintf('080000001%02d', $number),
                    'bio' => 'Profil fiktif QA untuk pengujian lokal SIPORA.', 'occupation_status' => $index % 2 === 0 ? 'university_student' : 'worker',
                    'occupation_title' => 'Peserta QA SIPORA',
                ])->save();
            }
            UserProfileVisibility::withTrashed()->updateOrCreate(['user_id' => $user->getKey()], [
                'is_profile_public' => $index !== 9, 'show_photo' => true, 'show_bio' => true, 'show_interests' => true,
                'show_skills' => $index !== 1, 'show_education' => $index !== 1, 'show_organization_experience' => $index !== 1,
                'show_community_membership' => true, 'show_activity_passport' => true, 'show_certificates' => true,
                'show_achievements' => $index !== 1, 'show_business_experience' => true, 'deleted_at' => null,
            ]);
            UserAddress::withTrashed()->updateOrCreate(['user_id' => $user->getKey(), 'address_type' => 'domicile'], [
                'administrative_area_id' => $area->getKey(), 'address_line' => 'Alamat fiktif QA '.$number,
                'rt' => sprintf('%03d', $number), 'rw' => '001', 'postal_code' => '52300', 'is_primary' => true, 'deleted_at' => null,
            ]);
            UserSocialAccount::query()->updateOrCreate(['user_id' => $user->getKey(), 'provider' => 'google'], [
                'provider_user_id' => 'qa-google-'.$number, 'provider_email' => $user->email, 'avatar_url' => null,
            ]);

            foreach ([$interests[$index % $interests->count()], $interests[($index + 3) % $interests->count()]] as $interest) {
                UserInterest::withTrashed()->updateOrCreate(['user_id' => $user->getKey(), 'interest_id' => $interest->getKey()], ['deleted_at' => null]);
            }
            UserSkill::withTrashed()->updateOrCreate(['user_id' => $user->getKey(), 'skill_id' => $skills[$index % $skills->count()]->getKey()], [
                'proficiency_level' => ['beginner', 'intermediate', 'advanced'][$index % 3], 'is_self_reported' => true, 'deleted_at' => null,
            ]);
            UserEducation::withTrashed()->updateOrCreate(['user_id' => $user->getKey(), 'institution_name' => 'Pusat Belajar QA '.$number], [
                'education_level' => $index % 2 === 0 ? 'S1' : 'SMA', 'field_of_study' => 'Pengembangan Pemuda',
                'start_date' => '2022-07-01', 'end_date' => $index % 3 === 0 ? null : '2025-06-30',
                'is_current' => $index % 3 === 0, 'description' => 'Data pendidikan fiktif QA.', 'deleted_at' => null,
            ]);
            OrganizationExperience::withTrashed()->updateOrCreate(['user_id' => $user->getKey(), 'organization_name' => 'Organisasi Eksternal QA '.$number], [
                'role_title' => 'Anggota', 'start_date' => '2023-01-01', 'end_date' => $index % 4 === 0 ? null : '2024-12-31',
                'is_current' => $index % 4 === 0, 'description' => 'Pengalaman organisasi mandiri fiktif.', 'deleted_at' => null,
            ]);
            UserAchievement::withTrashed()->updateOrCreate(['user_id' => $user->getKey(), 'title' => 'Prestasi QA '.$number], [
                'issuer_name' => 'Penyelenggara Fiktif QA', 'achievement_date' => '2025-08-17',
                'description' => 'Prestasi fiktif dan dilaporkan sendiri.', 'verification_status' => 'self_reported',
                'verified_by' => null, 'verified_at' => null, 'deleted_at' => null,
            ]);
            UserCertificate::withTrashed()->updateOrCreate(['user_id' => $user->getKey(), 'certificate_number' => sprintf('EXT-QA-%03d', $number)], [
                'participation_id' => null, 'source_type' => UserCertificate::SOURCE_EXTERNAL, 'name' => 'Sertifikat Eksternal QA '.$number,
                'issuer_name' => 'Penyelenggara Eksternal Fiktif', 'verification_code' => null, 'issued_at' => '2025-08-17',
                'expires_at' => null, 'file_path' => null, 'external_url' => null, 'verification_status' => 'self_reported', 'deleted_at' => null,
            ]);
        }
    }

    private function seedIdentityHistory(): void
    {
        $admin = User::query()->where('email', 'admin@sipora.test')->firstOrFail();
        $statuses = ['verified', 'pending', 'revision', 'rejected'];
        foreach ($this->youth() as $index => $user) {
            $number = sprintf('DEV-QA-ID-%03d', $index + 1);
            $status = $statuses[$index % count($statuses)];
            $type = ['ktp', 'kia', 'student_card'][$index % 3];
            $reviewed = $status !== 'pending';
            $identity = UserIdentity::withTrashed()->firstOrNew(['user_id' => $user->getKey()]);
            $identity->forceFill([
                'national_id_hash' => hash('sha256', $number), 'national_id_ciphertext' => Crypt::encryptString($number),
                'verification_status' => $status, 'verification_method' => $type,
                'verified_at' => $status === 'verified' ? now()->subDays(20) : null,
                'verified_by' => $status === 'verified' ? $admin->getKey() : null, 'deleted_at' => null,
            ])->save();
            $path = 'identity-verifications/qa/'.$user->email.'.pdf';
            $contents = "%PDF-1.4\n% SIPORA QA dummy identity document\n%%EOF\n";
            Storage::disk('private')->put($path, $contents);
            UserIdentityVerification::withTrashed()->updateOrCreate(['user_identity_id' => $identity->getKey(), 'document_type' => $type], [
                'document_number_hash' => hash('sha256', $number), 'document_number_ciphertext' => Crypt::encryptString($number),
                'document_path' => $path, 'document_sha256' => hash('sha256', $contents), 'status' => $status,
                'submitted_at' => now()->subDays(24), 'reviewed_at' => $reviewed ? now()->subDays(20) : null,
                'reviewed_by' => $reviewed ? $admin->getKey() : null,
                'review_notes' => in_array($status, ['revision', 'rejected'], true) ? 'Catatan keputusan fiktif QA.' : null,
                'metadata' => ['development_fixture' => true, 'qa' => true, 'mime_type' => 'application/pdf'], 'deleted_at' => null,
            ]);
        }
    }

    private function seedCommunityCoverage(): void
    {
        $verifier = User::query()->where('email', 'verifier@sipora.test')->firstOrFail();
        $areas = AdministrativeArea::query()->where('area_level', 'district')->orderBy('code')->get();
        $categories = OrganizationCategory::query()->orderBy('slug')->get();
        $states = [
            ['pending_review', 'inactive', 'pending'], ['revision', 'inactive', 'revision'], ['rejected', 'inactive', 'rejected'],
            ['draft', 'inactive', null], ['pending_review', 'inactive', 'pending'],
        ];
        foreach (range(6, 10) as $offset => $number) {
            $owner = $this->user('youth'.$number.'@sipora.test');
            [$review, $operational, $requestStatus] = $states[$offset];
            $organization = Organization::withTrashed()->updateOrCreate(['slug' => 'community-qa-'.$number], [
                'created_by_user_id' => $owner->getKey(), 'category_id' => $categories[$offset % $categories->count()]->getKey(),
                'administrative_area_id' => $areas[$offset % $areas->count()]->getKey(), 'name' => 'Community QA '.$number,
                'description' => 'Community fiktif untuk menguji status pengajuan.', 'contact_email' => 'community-qa-'.$number.'@example.test',
                'contact_phone' => sprintf('080000002%02d', $number), 'address_text' => 'Kabupaten Pemalang',
                'social_links' => ['instagram' => 'https://example.test/community-qa-'.$number], 'review_status' => $review,
                'operational_status' => $operational, 'approved_at' => null, 'approved_by' => null, 'deleted_at' => null,
            ]);
            if ($requestStatus) {
                OrganizationVerificationRequest::withTrashed()->updateOrCreate(['organization_id' => $organization->getKey(), 'submitted_at' => '2026-01-'.sprintf('%02d', $number).' 08:00:00'], [
                    'submitted_by' => $owner->getKey(), 'status' => $requestStatus, 'submission_notes' => 'Pengajuan fiktif QA.',
                    'reviewed_by' => $requestStatus === 'pending' ? null : $verifier->getKey(),
                    'reviewed_at' => $requestStatus === 'pending' ? null : '2026-01-20 08:00:00',
                    'review_notes' => $requestStatus === 'pending' ? null : 'Catatan review fiktif QA.', 'deleted_at' => null,
                ]);
            }
        }

        $approvedOrganization = Organization::query()->where('slug', 'komunitas-programmer-pemalang')->firstOrFail();
        foreach (range(1, 3) as $number) {
            OrganizationVerificationRequest::withTrashed()->updateOrCreate(['organization_id' => $approvedOrganization->getKey(), 'submitted_at' => '2025-01-0'.$number.' 08:00:00'], [
                'submitted_by' => $approvedOrganization->created_by_user_id, 'status' => $number < 3 ? 'revision' : 'approved',
                'submission_notes' => 'Riwayat pengajuan fiktif QA.', 'reviewed_by' => $verifier->getKey(),
                'reviewed_at' => '2025-01-1'.$number.' 08:00:00', 'review_notes' => $number < 3 ? 'Riwayat revisi fiktif QA.' : null,
                'deleted_at' => null,
            ]);
        }

        foreach ($this->youth()->slice(5) as $index => $user) {
            OrganizationMembership::withTrashed()->updateOrCreate(['organization_id' => $approvedOrganization->getKey(), 'user_id' => $user->getKey()], [
                'access_role' => OrganizationMembership::ROLE_MEMBER, 'position_title' => null,
                'membership_status' => OrganizationMembership::STATUS_ACTIVE, 'requested_at' => now()->subMonths(2),
                'approved_at' => now()->subMonths(2)->addDay(), 'approved_by' => $approvedOrganization->created_by_user_id,
                'ended_at' => null, 'deleted_at' => null,
            ]);
        }
    }

    private function seedActivityCoverage(): void
    {
        $verifier = $this->user('verifier@sipora.test');
        $recorder = $this->user('youth1@sipora.test');
        $activities = Activity::query()->orderBy('slug')->get();
        foreach ($activities as $activity) {
            if (! $activity->sessions()->exists()) {
                ActivitySession::withTrashed()->updateOrCreate(['activity_id' => $activity->getKey(), 'session_number' => 1], [
                    'title' => 'Sesi Utama', 'description' => 'Sesi default fiktif QA.', 'start_at' => $activity->start_at,
                    'end_at' => $activity->end_at, 'venue_name' => $activity->venue_name, 'address_text' => $activity->address_text,
                    'meeting_url' => null, 'notes' => null, 'deleted_at' => null,
                ]);
            }
        }

        $reviewable = $activities->whereIn('review_status', ['approved', 'revision'])->values();
        foreach (range(1, 10) as $index) {
            $activity = $reviewable[($index - 1) % $reviewable->count()];
            ActivityReview::withTrashed()->updateOrCreate(['activity_id' => $activity->getKey(), 'reviewed_at' => '2025-02-'.sprintf('%02d', $index).' 08:00:00'], [
                'reviewer_id' => $verifier->getKey(), 'decision' => $index % 2 ? 'revision' : 'rejected',
                'review_notes' => 'Riwayat keputusan fiktif QA '.$index.'.', 'deleted_at' => null,
            ]);
        }

        foreach ($this->youth() as $index => $user) {
            $activity = $activities[$index % $activities->count()];
            $participation = ActivityParticipation::withTrashed()->updateOrCreate(['activity_id' => $activity->getKey(), 'user_id' => $user->getKey()], [
                'activity_role' => 'participant', 'registration_status' => ActivityParticipation::REGISTRATION_ACCEPTED,
                'completion_status' => ActivityParticipation::COMPLETION_PENDING, 'registration_notes' => 'Partisipasi fiktif QA.',
                'requested_at' => now()->subWeeks(2), 'reviewed_at' => now()->subWeeks(2)->addDay(), 'reviewed_by' => $recorder->getKey(),
                'completed_at' => null, 'deleted_at' => null,
            ]);
            $session = $activity->sessions()->orderBy('session_number')->firstOrFail();
            ActivityAttendance::withTrashed()->updateOrCreate(['activity_session_id' => $session->getKey(), 'participation_id' => $participation->getKey()], [
                'attendance_status' => [ActivityAttendance::STATUS_PRESENT, ActivityAttendance::STATUS_ABSENT, ActivityAttendance::STATUS_EXCUSED][$index % 3],
                'checked_in_at' => $index % 3 === 0 ? $session->start_at->copy()->addMinutes(5) : null,
                'checked_out_at' => $index % 3 === 0 ? $session->end_at->copy()->subMinutes(5) : null,
                'recorded_by' => $recorder->getKey(), 'notes' => 'Presensi fiktif QA.', 'deleted_at' => null,
            ]);
        }
    }

    private function seedProgramCoverage(): void
    {
        $organization = Organization::query()->where('slug', 'komunitas-programmer-pemalang')->firstOrFail();
        $creator = $this->user('youth1@sipora.test');
        $verifier = $this->user('verifier@sipora.test');
        $categories = ProgramCategory::query()->orderBy('slug')->get();
        foreach (range(9, 10) as $offset => $number) {
            $program = Program::withTrashed()->updateOrCreate(['slug' => 'program-qa-'.$number], [
                'organization_id' => $organization->getKey(), 'category_id' => $categories[$offset % $categories->count()]->getKey(),
                'created_by_user_id' => $creator->getKey(), 'title' => 'Program QA '.$number,
                'description' => 'Program rencana fiktif QA.', 'objectives' => 'Menguji keadaan rencana tanpa publikasi.',
                'start_date' => null, 'end_date' => null, 'execution_status' => Program::STATUS_PLANNED, 'deleted_at' => null,
            ]);
            ProgramProposal::withTrashed()->updateOrCreate(['program_id' => $program->getKey(), 'version' => 1], [
                'status' => ProgramProposal::STATUS_DRAFT, 'requested_budget' => null, 'proposal_document_path' => null,
                'submitted_at' => null, 'reviewed_at' => null, 'reviewed_by' => null, 'review_notes' => null, 'deleted_at' => null,
            ]);
        }

        $program = Program::query()->where('slug', 'program-pemuda-digital-2026')->firstOrFail();
        $activity = $program->activities()->first();
        foreach (range(1, 9) as $index) {
            $logbook = ProgramLogbook::withTrashed()->updateOrCreate(['program_id' => $program->getKey(), 'log_date' => '2025-03-'.sprintf('%02d', $index)], [
                'activity_id' => $activity?->getKey(), 'created_by_user_id' => $creator->getKey(),
                'summary' => 'Logbook historis fiktif QA '.$index.'.', 'obstacles' => null, 'solutions' => null,
                'progress_percent' => min(90, $index * 10), 'status' => ProgramLogbook::STATUS_APPROVED,
                'submitted_at' => '2025-03-'.sprintf('%02d', $index).' 12:00:00', 'reviewed_at' => '2025-03-'.sprintf('%02d', $index).' 15:00:00',
                'reviewed_by' => $verifier->getKey(), 'review_notes' => null, 'deleted_at' => null,
            ]);
            $path = 'logbooks/qa/logbook-'.$index.'.pdf';
            Storage::disk('program_logbook_media')->put($path, "%PDF-1.4\n% SIPORA QA logbook media\n");
            ProgramLogbookMedia::withTrashed()->updateOrCreate(['logbook_id' => $logbook->getKey(), 'file_path' => $path], [
                'uploaded_by' => $creator->getKey(), 'caption' => 'Media privat fiktif QA '.$index, 'deleted_at' => null,
            ]);
        }

        foreach (range(2, 6) as $version) {
            $report = FinancialReport::withTrashed()->updateOrCreate(['program_id' => $program->getKey(), 'version' => $version], [
                'status' => FinancialReport::STATUS_DRAFT, 'notes' => 'E-LPJ draft fiktif QA versi '.$version,
                'submitted_at' => null, 'reviewed_at' => null, 'reviewed_by' => null, 'review_notes' => null, 'deleted_at' => null,
            ]);
            FinancialItem::withTrashed()->updateOrCreate(['financial_report_id' => $report->getKey(), 'description' => 'Belanja fiktif QA versi '.$version], [
                'transaction_type' => FinancialItem::TYPE_EXPENSE, 'transaction_date' => '2025-04-'.sprintf('%02d', $version),
                'amount' => (string) ($version * 100000), 'receipt_path' => null, 'deleted_at' => null,
            ]);
        }

        $completed = Program::query()->where('slug', 'program-tuntas-pemuda')->firstOrFail();
        foreach (range(1, 10) as $index) {
            ProgramEvaluation::withTrashed()->updateOrCreate(['program_id' => $completed->getKey(), 'evaluated_at' => '2025-05-'.sprintf('%02d', $index).' 08:00:00'], [
                'verifier_id' => $verifier->getKey(), 'decision' => $index % 2 ? ProgramEvaluation::DECISION_REVISION : ProgramEvaluation::DECISION_REJECTED,
                'evaluation_notes' => 'Riwayat evaluasi fiktif QA '.$index.'.', 'deleted_at' => null,
            ]);
        }
    }

    private function seedOpportunityCoverage(): void
    {
        $admin = $this->user('admin@sipora.test');
        $area = AdministrativeArea::query()->where('area_level', 'regency')->first()
            ?? AdministrativeArea::query()->where('area_level', 'district')->firstOrFail();
        $categories = OpportunityCategory::query()->orderBy('slug')->get();
        foreach (range(6, 10) as $offset => $number) {
            Opportunity::withTrashed()->updateOrCreate(['slug' => 'opportunity-qa-'.$number], [
                'category_id' => $categories[$offset % $categories->count()]->getKey(), 'organization_id' => null,
                'created_by_user_id' => $admin->getKey(), 'title' => 'Opportunity QA '.$number,
                'provider_name' => 'Penyedia Fiktif QA', 'description' => 'Opportunity draft/arsip untuk pengujian kebocoran publik.',
                'administrative_area_id' => $area->getKey(), 'location_text' => 'Kabupaten Pemalang',
                'external_url' => 'https://example.test/opportunity-qa-'.$number, 'deadline_at' => now()->addDays($number),
                'starts_at' => now()->addDays($number + 7), 'ends_at' => now()->addDays($number + 14),
                'publication_status' => $number % 2 ? Opportunity::STATUS_DRAFT : Opportunity::STATUS_ARCHIVED,
                'published_at' => null, 'deleted_at' => null,
            ]);
        }

        $opportunities = Opportunity::query()->where('publication_status', Opportunity::STATUS_PUBLISHED)->orderBy('slug')->get();
        foreach ($this->youth() as $index => $user) {
            OpportunityBookmark::withTrashed()->updateOrCreate(['user_id' => $user->getKey(), 'opportunity_id' => $opportunities[$index % $opportunities->count()]->getKey()], ['deleted_at' => null]);
        }
    }

    private function seedNotificationsAndAudit(): void
    {
        $admin = $this->user('admin@sipora.test');
        foreach ($this->youth() as $index => $user) {
            $number = $index + 1;
            UserNotification::withTrashed()->updateOrCreate(['user_id' => $user->getKey(), 'notification_type' => 'qa_fixture_'.$number], [
                'title' => 'Notifikasi QA '.$number, 'body' => 'Notifikasi fiktif untuk pengujian lokal.',
                'data' => ['qa' => true], 'read_at' => $index % 2 ? now()->subDay() : null, 'deleted_at' => null,
            ]);
            if (! AuditEntry::query()->where('event', 'qa_fixture_'.$number)->exists()) {
                activity()->causedBy($admin)->performedOn($user)->event('qa_fixture_'.$number)
                    ->withProperties(['qa' => true, 'sequence' => $number])->log('Aktivitas audit fiktif QA');
            }
        }
    }

    /** @return Collection<int, User> */
    private function youth()
    {
        return User::role('youth')->get()
            ->sortBy(fn (User $user): int => (int) Str::between($user->email, 'youth', '@'))
            ->values();
    }

    private function user(string $email): User
    {
        return User::query()->where('email', $email)->firstOrFail();
    }
}
