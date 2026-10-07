<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivityAttendance;
use App\Models\ActivityCategory;
use App\Models\ActivityParticipation;
use App\Models\ActivityReview;
use App\Models\ActivitySession;
use App\Models\AdministrativeArea;
use App\Models\Interest;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationMembership;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\ProgramProposal;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserIdentity;
use App\Models\UserInterest;
use App\Models\UserProfile;
use App\Models\UserProfileVisibility;
use App\Models\UserSkill;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ActivityAttendanceScenarioSeeder extends Seeder
{
    private const ORGANIZATION_SLUG = 'komunitas-pemuda-digital-pemalang';

    private const PROGRAM_SLUG = 'pemalang-youth-digital-empowerment-2026';

    private const ACTIVITY_SLUG = 'workshop-web-development-untuk-pemuda';

    public function run(): void
    {
        if (app()->environment('production') || DB::connection()->getDatabaseName() !== 'sipora') {
            throw new RuntimeException('Seeder ini hanya untuk database development sipora.');
        }

        DB::transaction(function (): void {
            $manager = User::query()->where('email', 'youth1@sipora.test')->firstOrFail();
            $verifier = User::query()->where('email', 'verifier@sipora.test')->firstOrFail();
            $admin = User::query()->where('email', 'admin@sipora.test')->firstOrFail();
            $district = AdministrativeArea::query()->where('code', '33.27.08')->firstOrFail();
            $skill = Skill::query()->where('slug', 'pemrograman-web-software')->firstOrFail();
            $interest = Interest::query()->where('slug', 'teknologi')->firstOrFail();

            $organization = Organization::withTrashed()->updateOrCreate(['slug' => self::ORGANIZATION_SLUG], [
                'created_by_user_id' => $manager->getKey(),
                'category_id' => OrganizationCategory::query()->where('slug', 'komunitas-teknologi')->firstOrFail()->getKey(),
                'administrative_area_id' => $district->getKey(),
                'name' => 'Komunitas Pemuda Digital Pemalang',
                'description' => 'Komunitas pengembangan keterampilan digital pemuda untuk skenario presensi lokal.',
                'contact_email' => 'pemuda-digital@sipora.test',
                'address_text' => 'Kabupaten Pemalang',
                'social_links' => (object) [],
                'review_status' => Organization::REVIEW_APPROVED,
                'operational_status' => Organization::OPERATIONAL_ACTIVE,
                'approved_at' => '2026-08-15 10:00:00',
                'approved_by' => $verifier->getKey(),
                'deleted_at' => null,
            ]);
            OrganizationMembership::withTrashed()->updateOrCreate(
                ['organization_id' => $organization->getKey(), 'user_id' => $manager->getKey()],
                ['access_role' => OrganizationMembership::ROLE_LEADER, 'position_title' => 'Ketua',
                    'membership_status' => OrganizationMembership::STATUS_ACTIVE,
                    'requested_at' => '2026-08-10 09:00:00', 'approved_at' => '2026-08-15 10:00:00',
                    'approved_by' => $manager->getKey(), 'ended_at' => null, 'deleted_at' => null]
            );

            $program = Program::withTrashed()->updateOrCreate(['slug' => self::PROGRAM_SLUG], [
                'organization_id' => $organization->getKey(),
                'category_id' => ProgramCategory::query()->where('slug', 'pengembangan-kapasitas-pemuda')->firstOrFail()->getKey(),
                'created_by_user_id' => $manager->getKey(),
                'title' => 'Pemalang Youth Digital Empowerment 2026',
                'description' => 'Program penguatan keterampilan digital pemuda Pemalang.',
                'objectives' => 'Membekali peserta dengan dasar pengembangan web dan pengalaman proyek.',
                'start_date' => '2026-08-01', 'end_date' => '2026-12-31',
                'execution_status' => Program::STATUS_RUNNING,
                'deleted_at' => null,
            ]);
            $proposalPath = 'proposals/development-'.self::PROGRAM_SLUG.'.pdf';
            Storage::disk('proposal_documents')->put($proposalPath, "%PDF-1.4\n% SIPORA development attendance scenario\n%%EOF\n");
            ProgramProposal::withTrashed()->updateOrCreate(
                ['program_id' => $program->getKey(), 'version' => 1],
                ['status' => ProgramProposal::STATUS_APPROVED, 'requested_budget' => 30000000,
                    'proposal_document_path' => $proposalPath, 'submitted_at' => '2026-08-05 09:00:00',
                    'reviewed_at' => '2026-08-10 10:00:00', 'reviewed_by' => $verifier->getKey(),
                    'review_notes' => 'Proposal skenario pengembangan disetujui.', 'deleted_at' => null]
            );

            $activity = Activity::withTrashed()->updateOrCreate(['slug' => self::ACTIVITY_SLUG], [
                'organization_id' => $organization->getKey(), 'program_id' => $program->getKey(),
                'category_id' => ActivityCategory::query()->where('slug', 'teknologi')->firstOrFail()->getKey(),
                'created_by_user_id' => $manager->getKey(),
                'title' => 'Workshop Web Development untuk Pemuda',
                'description' => 'Workshop tiga sesi untuk belajar dasar web hingga mempresentasikan mini project.',
                'location_type' => 'offline', 'venue_name' => 'Gedung Pemuda Pemalang',
                'administrative_area_id' => $district->getKey(), 'address_text' => 'Kabupaten Pemalang',
                'start_at' => '2026-09-14 09:00:00', 'end_at' => '2026-09-16 12:00:00',
                'registration_open_at' => '2026-08-20 09:00:00', 'registration_close_at' => '2026-09-10 17:00:00',
                'quota' => 25, 'registration_mode' => 'approval_required',
                'min_age' => 16, 'max_age' => 30, 'requires_identity_verification' => false,
                'members_only' => false, 'certificate_enabled' => true,
                'review_status' => Activity::REVIEW_APPROVED,
                'publication_status' => Activity::PUBLICATION_PUBLISHED,
                'execution_status' => Activity::EXECUTION_COMPLETED,
                'published_at' => '2026-08-20 09:00:00', 'deleted_at' => null,
            ]);
            ActivityReview::withTrashed()->updateOrCreate(
                ['activity_id' => $activity->getKey(), 'decision' => Activity::REVIEW_APPROVED],
                ['reviewer_id' => $verifier->getKey(), 'review_notes' => null,
                    'reviewed_at' => '2026-08-19 10:00:00', 'deleted_at' => null]
            );

            $sessionNames = [
                1 => 'Pengenalan Web Development',
                2 => 'Praktik HTML, CSS, dan JavaScript',
                3 => 'Mini Project & Presentasi',
            ];
            $sessions = [];
            foreach ($sessionNames as $number => $title) {
                $start = CarbonImmutable::parse('2026-09-'.(13 + $number).' 09:00:00');
                $sessions[$number] = ActivitySession::withTrashed()->updateOrCreate(
                    ['activity_id' => $activity->getKey(), 'session_number' => $number],
                    ['title' => $title, 'description' => 'Sesi '.$number.' workshop pengembangan web.',
                        'start_at' => $start, 'end_at' => $start->addHours(3),
                        'venue_name' => 'Gedung Pemuda Pemalang', 'address_text' => 'Kabupaten Pemalang',
                        'notes' => null, 'deleted_at' => null]
                );
            }

            $names = [
                'Aditya Pratama', 'Nabila Safitri', 'Fajar Maulana', 'Alya Putri', 'Bagas Ramadhan',
                'Dinda Maharani', 'Rizky Firmansyah', 'Siti Aulia', 'Dimas Nugroho', 'Nadia Kurniawati',
                'Raka Saputra', 'Anisa Rahma', 'Galih Prakoso', 'Intan Permata', 'Farhan Hakim',
                'Laila Zahra', 'Yoga Wibowo', 'Maya Salsabila', 'Bima Setiawan', 'Putri Amelia',
            ];
            foreach ($names as $offset => $name) {
                $number = $offset + 1;
                $user = $this->youth($number, $name, $district, $skill, $interest, $admin);
                $statuses = [
                    1 => $this->attendanceStatus(1, $number),
                    2 => $this->attendanceStatus(2, $number),
                    3 => $this->attendanceStatus(3, $number),
                ];
                $completed = count(array_filter($statuses, fn (string $status) => $status === ActivityAttendance::STATUS_PRESENT)) === 3;
                $participation = ActivityParticipation::withTrashed()->updateOrCreate(
                    ['activity_id' => $activity->getKey(), 'user_id' => $user->getKey()],
                    ['activity_role' => 'participant', 'registration_status' => ActivityParticipation::REGISTRATION_ACCEPTED,
                        'completion_status' => $completed ? ActivityParticipation::COMPLETION_COMPLETED : ActivityParticipation::COMPLETION_PENDING,
                        'registration_notes' => null,
                        'requested_at' => CarbonImmutable::parse('2026-09-05 09:00:00')->addMinutes($number),
                        'reviewed_at' => '2026-09-06 10:00:00', 'reviewed_by' => $manager->getKey(),
                        'completed_at' => $completed ? '2026-09-16 13:00:00' : null, 'deleted_at' => null]
                );
                foreach ($statuses as $sessionNumber => $status) {
                    $session = $sessions[$sessionNumber];
                    $present = $status === ActivityAttendance::STATUS_PRESENT;
                    ActivityAttendance::withTrashed()->updateOrCreate(
                        ['activity_session_id' => $session->getKey(), 'participation_id' => $participation->getKey()],
                        ['attendance_status' => $status,
                            'checked_in_at' => $present ? $session->start_at->copy()->addMinutes(2 + $number % 9) : null,
                            'checked_out_at' => $present ? $session->end_at->copy()->subMinutes(3 + $number % 5) : null,
                            'recorded_by' => $manager->getKey(),
                            'notes' => $status === ActivityAttendance::STATUS_EXCUSED ? 'Izin karena keperluan pribadi.' : null,
                            'deleted_at' => null]
                    );
                }
            }

            // The extra pending registration makes the 20-row participant table paginate without changing its page size.
            $pendingYouth = User::query()->where('email', 'youth2@sipora.test')->firstOrFail();
            ActivityParticipation::withTrashed()->updateOrCreate(
                ['activity_id' => $activity->getKey(), 'user_id' => $pendingYouth->getKey()],
                ['activity_role' => 'participant', 'registration_status' => ActivityParticipation::REGISTRATION_PENDING,
                    'completion_status' => ActivityParticipation::COMPLETION_PENDING, 'registration_notes' => null,
                    'requested_at' => '2026-09-09 10:00:00', 'reviewed_at' => null, 'reviewed_by' => null,
                    'completed_at' => null, 'deleted_at' => null]
            );
        });
    }

    private function youth(int $number, string $name, AdministrativeArea $district, Skill $skill, Interest $interest, User $admin): User
    {
        $email = sprintf('attendance.youth%02d@sipora.test', $number);
        $user = User::query()->firstOrCreate(['email' => $email], [
            'name' => $name, 'password' => Hash::make(UserSeeder::PASSWORD),
        ]);
        $user->forceFill(['name' => $name, 'email_verified_at' => '2026-08-15 10:00:00'])->save();
        $user->syncRoles(['youth']);

        UserProfile::withTrashed()->updateOrCreate(['user_id' => $user->getKey()], [
            'public_slug' => sprintf('attendance-youth-%02d', $number), 'full_name' => $name,
            'birth_place' => 'Pemalang', 'birth_date' => sprintf('200%d-%02d-%02d', $number % 6, 1 + $number % 12, 1 + $number % 27),
            'gender' => $number % 2 === 0 ? 'female' : 'male',
            'phone' => sprintf('080000001%03d', $number),
            'bio' => 'Peserta dummy workshop pengembangan web SIPORA.',
            'occupation_status' => 'student', 'occupation_title' => 'Pelajar / Mahasiswa',
            'deleted_at' => null,
        ]);
        UserAddress::withTrashed()->updateOrCreate(
            ['user_id' => $user->getKey(), 'address_type' => 'domicile'],
            ['administrative_area_id' => $district->getKey(), 'address_line' => 'Alamat dummy di Kabupaten Pemalang',
                'is_primary' => true, 'deleted_at' => null]
        );
        UserProfileVisibility::withTrashed()->updateOrCreate(['user_id' => $user->getKey()], [
            'is_profile_public' => true, 'show_bio' => true, 'show_interests' => true,
            'show_skills' => true, 'show_activity_passport' => true,
            'show_certificates' => true, 'deleted_at' => null,
        ]);

        $identity = UserIdentity::withTrashed()->firstOrNew(['user_id' => $user->getKey()]);
        if (! $identity->exists) {
            $dummyId = sprintf('DEV-ATTENDANCE-%02d', $number);
            $identity->forceFill([
                'national_id_hash' => hash('sha256', $dummyId),
                'national_id_ciphertext' => Crypt::encryptString($dummyId),
            ]);
        }
        $identity->forceFill([
            'verification_status' => UserIdentity::STATUS_VERIFIED,
            'verification_method' => 'ktp', 'verified_at' => '2026-08-15 10:00:00',
            'verified_by' => $admin->getKey(), 'deleted_at' => null,
        ])->save();
        UserSkill::withTrashed()->updateOrCreate(
            ['user_id' => $user->getKey(), 'skill_id' => $skill->getKey()],
            ['proficiency_level' => 'beginner', 'is_self_reported' => true, 'deleted_at' => null]
        );
        UserInterest::withTrashed()->updateOrCreate(
            ['user_id' => $user->getKey(), 'interest_id' => $interest->getKey()],
            ['deleted_at' => null]
        );

        return $user;
    }

    private function attendanceStatus(int $session, int $youth): string
    {
        $absent = [1 => [5, 12], 2 => [4], 3 => [3, 5, 18]];
        $excused = [1 => [7, 19], 2 => [15], 3 => []];

        return match (true) {
            in_array($youth, $absent[$session], true) => ActivityAttendance::STATUS_ABSENT,
            in_array($youth, $excused[$session], true) => ActivityAttendance::STATUS_EXCUSED,
            default => ActivityAttendance::STATUS_PRESENT,
        };
    }
}
