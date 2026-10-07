<?php

namespace Database\Seeders;

use App\Actions\Manager\IssueActivityCertificateAction;
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
use App\Models\Skill;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserCertificate;
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
use RuntimeException;

class YouthPassportScenarioSeeder extends Seeder
{
    public const EMAIL = 'passport.youth@sipora.test';

    private const COMMUNITY_SLUG = 'komunitas-pemuda-digital-pemalang';

    public function run(): void
    {
        if (app()->environment('production')
            || (DB::connection()->getDatabaseName() !== 'sipora' && ! app()->environment('testing'))) {
            throw new RuntimeException('Seeder Passport hanya boleh dijalankan pada database development sipora atau saat testing.');
        }

        $summary = DB::transaction(function (): array {
            $manager = User::query()->where('email', 'youth1@sipora.test')->firstOrFail();
            $verifier = User::query()->where('email', 'verifier@sipora.test')->firstOrFail();
            $district = AdministrativeArea::query()->where('code', '33.27.08')->firstOrFail();
            $youth = $this->youth($district, $verifier);
            $community = $this->community($manager, $verifier, $district);

            $records = [
                [
                    'slug' => 'workshop-web-development-untuk-pemuda',
                    'title' => 'Workshop Web Development untuk Pemuda',
                    'category' => 'teknologi',
                    'sessions' => [
                        ['Pengenalan Web Development', '2026-09-14'],
                        ['Praktik HTML, CSS, dan JavaScript', '2026-09-15'],
                        ['Mini Project & Presentasi', '2026-09-16'],
                    ],
                    'role' => 'participant', 'attendance' => ['present', 'present', 'present'],
                    'certificate' => true,
                ],
                [
                    'slug' => 'passport-pelatihan-public-speaking-dasar',
                    'title' => 'Pelatihan Public Speaking Dasar',
                    'category' => 'pendidikan',
                    'sessions' => [
                        ['Dasar Komunikasi dan Percaya Diri', '2026-09-18'],
                        ['Latihan Presentasi dan Umpan Balik', '2026-09-19'],
                    ],
                    'role' => 'participant', 'attendance' => ['present', 'present'],
                    'certificate' => true,
                ],
                [
                    'slug' => 'passport-volunteer-pemalang-youth-festival',
                    'title' => 'Volunteer Pemalang Youth Festival',
                    'category' => 'sosial',
                    'sessions' => [['Pelaksanaan dan Layanan Peserta Festival', '2026-09-22']],
                    'role' => 'volunteer', 'attendance' => ['present'],
                    'certificate' => true,
                ],
                [
                    'slug' => 'passport-seminar-kewirausahaan-digital',
                    'title' => 'Seminar Kewirausahaan Digital',
                    'category' => 'kewirausahaan',
                    'sessions' => [['Peluang Usaha Digital Pemuda', '2026-09-26']],
                    'role' => 'participant', 'attendance' => ['present'],
                    'certificate' => true,
                ],
                [
                    'slug' => 'passport-bootcamp-desain-konten-digital',
                    'title' => 'Bootcamp Desain Konten Digital',
                    'category' => 'kreatif',
                    'sessions' => [
                        ['Strategi Konten Digital', '2026-09-28'],
                        ['Praktik Desain Visual', '2026-09-29'],
                        ['Produksi Konten', '2026-09-30'],
                        ['Presentasi Portofolio Konten', '2026-10-01'],
                    ],
                    'role' => 'participant', 'attendance' => ['present', 'present', 'absent', 'present'],
                    // Domain saat ini tidak mempunyai ambang kehadiran untuk sertifikat.
                    // Activity ini tidak menyediakan sertifikat agar skenario tanpa sertifikat tetap valid.
                    'certificate' => false,
                ],
            ];

            foreach ($records as $record) {
                $activity = $this->activity($record, $community, $manager, $verifier, $district);
                $participation = $this->participation($record, $activity, $youth, $manager);
                foreach ($record['sessions'] as $index => [$title, $date]) {
                    $session = $this->session($activity, $index + 1, $title, $date);
                    $status = $record['attendance'][$index];
                    $present = $status === ActivityAttendance::STATUS_PRESENT;
                    ActivityAttendance::withTrashed()->updateOrCreate(
                        ['activity_session_id' => $session->getKey(), 'participation_id' => $participation->getKey()],
                        ['attendance_status' => $status,
                            'checked_in_at' => $present ? $session->start_at->copy()->addMinutes(5) : null,
                            'checked_out_at' => $present ? $session->end_at->copy()->subMinutes(5) : null,
                            'recorded_by' => $manager->getKey(),
                            'notes' => $present ? null : 'Tidak hadir pada sesi praktik.',
                            'deleted_at' => null]
                    );
                }
                if ($record['certificate']) {
                    $existing = UserCertificate::withTrashed()->where('participation_id', $participation->getKey())->first();
                    if ($existing) {
                        if ($existing->trashed()) {
                            $existing->restore();
                        }
                    } else {
                        app(IssueActivityCertificateAction::class)->execute($manager, $activity, $participation);
                    }
                }
            }

            $entries = app(\App\Services\Activity\ActivityPassportService::class)->queryFor($youth)->get();
            return [
                'youth' => $youth->email,
                'passport' => $entries->count(),
                'certificates' => $entries->filter(fn (ActivityParticipation $entry): bool => $entry->certificate !== null)->count(),
                'attendance' => $entries->map(fn (ActivityParticipation $entry): string => $entry->activity->title.': '.$entry->present_count.'/'.($entry->present_count + $entry->absent_count + $entry->excused_count))->all(),
            ];
        });

        $this->command?->info('Youth: '.$summary['youth'].' | Passport: '.$summary['passport'].' | Sertifikat: '.$summary['certificates']);
        foreach ($summary['attendance'] as $line) {
            $this->command?->line($line);
        }
    }

    private function youth(AdministrativeArea $district, User $verifier): User
    {
        $youth = User::query()->firstOrCreate(['email' => self::EMAIL], [
            'name' => 'Nadira Putri Prameswari', 'password' => Hash::make(UserSeeder::PASSWORD),
        ]);
        $youth->forceFill(['name' => 'Nadira Putri Prameswari', 'email_verified_at' => '2026-08-20 09:00:00'])->save();
        $youth->syncRoles(['youth']);
        UserProfile::withTrashed()->updateOrCreate(['user_id' => $youth->getKey()], [
            'public_slug' => 'nadira-putri-passport-demo', 'full_name' => 'Nadira Putri Prameswari',
            'birth_place' => 'Pemalang', 'birth_date' => '2003-04-12', 'gender' => 'female',
            'phone' => '080000009501', 'bio' => 'Pemuda Pemalang yang aktif belajar, berkarya, dan menjadi relawan.',
            'occupation_status' => 'university_student', 'occupation_title' => 'Mahasiswa',
            'deleted_at' => null,
        ]);
        UserAddress::withTrashed()->updateOrCreate(
            ['user_id' => $youth->getKey(), 'address_type' => 'domicile'],
            ['administrative_area_id' => $district->getKey(), 'address_line' => 'Alamat fiktif khusus pengembangan',
                'is_primary' => true, 'deleted_at' => null]
        );
        UserProfileVisibility::withTrashed()->updateOrCreate(['user_id' => $youth->getKey()], [
            'is_profile_public' => true, 'show_bio' => true, 'show_interests' => true, 'show_skills' => true,
            'show_activity_passport' => true, 'show_certificates' => true, 'deleted_at' => null,
        ]);
        $identity = UserIdentity::withTrashed()->firstOrNew(['user_id' => $youth->getKey()]);
        if (! $identity->exists) {
            $dummyId = 'DEV-PASSPORT-NADIRA';
            $identity->forceFill([
                'national_id_hash' => hash('sha256', $dummyId),
                'national_id_ciphertext' => Crypt::encryptString($dummyId),
            ]);
        }
        $identity->forceFill([
            'verification_status' => UserIdentity::STATUS_VERIFIED, 'verification_method' => 'ktp',
            'verified_at' => '2026-08-20 10:00:00', 'verified_by' => $verifier->getKey(), 'deleted_at' => null,
        ])->save();
        foreach (['teknologi', 'seni-kreatif'] as $slug) {
            $interest = Interest::query()->where('slug', $slug)->firstOrFail();
            UserInterest::withTrashed()->updateOrCreate(
                ['user_id' => $youth->getKey(), 'interest_id' => $interest->getKey()], ['deleted_at' => null]
            );
        }
        foreach (['pemrograman-web-software', 'public-speaking-komunikasi'] as $slug) {
            $skill = Skill::query()->where('slug', $slug)->firstOrFail();
            UserSkill::withTrashed()->updateOrCreate(
                ['user_id' => $youth->getKey(), 'skill_id' => $skill->getKey()],
                ['proficiency_level' => 'beginner', 'is_self_reported' => true, 'deleted_at' => null]
            );
        }

        return $youth;
    }

    private function community(User $manager, User $verifier, AdministrativeArea $district): Organization
    {
        $category = OrganizationCategory::query()->firstOrCreate(
            ['slug' => 'komunitas-teknologi'], ['name' => 'Komunitas Teknologi', 'description' => 'Kategori komunitas contoh khusus pengembangan.']
        );
        $community = Organization::withTrashed()->firstOrNew(['slug' => self::COMMUNITY_SLUG]);
        if (! $community->exists) {
            $community->fill([
                'created_by_user_id' => $manager->getKey(), 'category_id' => $category->getKey(),
                'administrative_area_id' => $district->getKey(), 'name' => 'Komunitas Pemuda Digital Pemalang',
                'description' => 'Komunitas pengembangan keterampilan digital pemuda Pemalang.',
                'social_links' => (object) [], 'address_text' => 'Kabupaten Pemalang',
            ]);
        }
        $community->forceFill([
            'review_status' => Organization::REVIEW_APPROVED,
            'operational_status' => Organization::OPERATIONAL_ACTIVE,
            'approved_at' => $community->approved_at ?? '2026-08-15 10:00:00',
            'approved_by' => $verifier->getKey(), 'deleted_at' => null,
        ])->save();

        return $community;
    }

    private function activity(array $record, Organization $community, User $manager, User $verifier, AdministrativeArea $district): Activity
    {
        $category = ActivityCategory::query()->where('slug', $record['category'])->firstOrFail();
        $first = CarbonImmutable::parse($record['sessions'][0][1].' 09:00:00');
        $last = CarbonImmutable::parse($record['sessions'][array_key_last($record['sessions'])][1].' 12:00:00');
        $activity = Activity::withTrashed()->updateOrCreate(['slug' => $record['slug']], [
            'organization_id' => $community->getKey(), 'category_id' => $category->getKey(),
            'created_by_user_id' => $manager->getKey(), 'title' => $record['title'],
            'description' => 'Kegiatan contoh untuk menguji rekam pengalaman Youth Passport.',
            'location_type' => 'offline', 'venue_name' => 'Gedung Pemuda Pemalang',
            'administrative_area_id' => $district->getKey(), 'address_text' => 'Kabupaten Pemalang',
            'start_at' => $first, 'end_at' => $last,
            'registration_open_at' => $first->subDays(21), 'registration_close_at' => $first->subDays(3),
            'quota' => 30, 'registration_mode' => 'approval_required',
            'min_age' => 16, 'max_age' => 35, 'requires_identity_verification' => false,
            'members_only' => false, 'certificate_enabled' => $record['certificate'],
            'review_status' => Activity::REVIEW_APPROVED, 'publication_status' => Activity::PUBLICATION_PUBLISHED,
            'execution_status' => Activity::EXECUTION_COMPLETED, 'published_at' => $first->subDays(21),
            'deleted_at' => null,
        ]);
        ActivityReview::withTrashed()->updateOrCreate(
            ['activity_id' => $activity->getKey(), 'decision' => Activity::REVIEW_APPROVED],
            ['reviewer_id' => $verifier->getKey(), 'review_notes' => 'Activity contoh disetujui.',
                'reviewed_at' => $first->subDays(20), 'deleted_at' => null]
        );

        return $activity;
    }

    private function participation(array $record, Activity $activity, User $youth, User $manager): ActivityParticipation
    {
        return ActivityParticipation::withTrashed()->updateOrCreate(
            ['activity_id' => $activity->getKey(), 'user_id' => $youth->getKey()],
            ['activity_role' => $record['role'],
                'registration_status' => ActivityParticipation::REGISTRATION_ACCEPTED,
                'completion_status' => ActivityParticipation::COMPLETION_COMPLETED,
                'requested_at' => $activity->start_at->copy()->subDays(10),
                'reviewed_at' => $activity->start_at->copy()->subDays(8),
                'reviewed_by' => $manager->getKey(),
                'completed_at' => $activity->end_at->copy()->addHour(),
                'registration_notes' => null, 'deleted_at' => null]
        );
    }

    private function session(Activity $activity, int $number, string $title, string $date): ActivitySession
    {
        $start = CarbonImmutable::parse($date.' 09:00:00');
        return ActivitySession::withTrashed()->updateOrCreate(
            ['activity_id' => $activity->getKey(), 'session_number' => $number],
            ['title' => $title, 'description' => 'Sesi '.$number.' '.$activity->title.'.',
                'start_at' => $start, 'end_at' => $start->addHours(3),
                'venue_name' => 'Gedung Pemuda Pemalang', 'address_text' => 'Kabupaten Pemalang',
                'notes' => null, 'deleted_at' => null]
        );
    }
}
