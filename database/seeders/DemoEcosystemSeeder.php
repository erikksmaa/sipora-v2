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
use App\Models\Opportunity;
use App\Models\OpportunityCategory;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationMembership;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\ProgramProposal;
use App\Models\User;
use App\Models\UserCertificate;
use App\Models\UserNotification;
use App\Notifications\ActivityRegistrationReviewed;
use App\Notifications\CommunityMembershipReviewed;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DemoEcosystemSeeder extends Seeder
{
    public function run(): void
    {
        $users = DemoYouthSeeder::users();
        $erik = $users->first();
        $verifier = User::where('email', 'verifier@sipora.test')->firstOrFail();
        $areas = AdministrativeArea::where('area_level', 'district')->orderBy('code')->get();
        $communities = [
            ['Komunitas Pemuda Digital Pemalang', 'teknologi'], ['Pemalang Creative Hub', 'kreatif'], ['Pemuda Peduli Lingkungan', 'sosial'],
            ['Forum Pemuda Taman', 'pendidikan'], ['Komunitas Desain Pemalang', 'kreatif'], ['Pemalang Public Speaking Club', 'pendidikan'],
            ['Youth Entrepreneur Pemalang', 'kewirausahaan'], ['Komunitas Fotografi Pemalang', 'kreatif'], ['Relawan Muda Pemalang', 'sosial'],
            ['Pemuda Olahraga Pemalang', 'olahraga'], ['Komunitas Literasi Petarukan', 'pendidikan'], ['Pemuda Inovasi Comal', 'teknologi'],
            ['Sanggar Seni Muda Belik', 'kreatif'], ['Forum Karier Randudongkal', 'pendidikan'], ['Relawan Pesisir Ulujami', 'sosial'],
        ];
        $organizations = [];
        foreach ($communities as $i => [$name, $category]) {
            $leader = $users[$i % 19];
            $cat = OrganizationCategory::withTrashed()->updateOrCreate(['slug' => 'komunitas-'.$category], ['name' => 'Komunitas '.ucfirst($category), 'deleted_at' => null]);
            $org = Organization::withTrashed()->updateOrCreate(['slug' => Str::slug($name)], [
                'name' => $name, 'created_by_user_id' => $leader->getKey(), 'category_id' => $cat->getKey(), 'administrative_area_id' => $areas[($i + 7) % 14]->getKey(),
                'description' => $name.' menjadi ruang belajar '.$category.', berbagi karya, dan kolaborasi pemuda di Pemalang.',
                'social_links' => (object) [], 'contact_email' => 'demo.community'.($i + 1).'@sipora.test', 'address_text' => 'Lokasi demo tingkat kecamatan',
                'review_status' => 'approved', 'operational_status' => 'active', 'approved_at' => now()->subDays(90 + $i), 'approved_by' => $verifier->getKey(), 'deleted_at' => null,
            ]);
            $organizations[] = $org;
            foreach (range(0, 3 + $i % 4) as $j) {
                $member = $users[($i + $j) % 20];
                OrganizationMembership::withTrashed()->updateOrCreate(['organization_id' => $org->getKey(), 'user_id' => $member->getKey()], [
                    'access_role' => $j === 0 ? 'leader' : ($j === 1 ? 'manager' : 'member'), 'position_title' => $j === 0 ? 'Ketua' : null,
                    'membership_status' => 'active', 'requested_at' => now()->subDays(100), 'approved_at' => now()->subDays(90), 'approved_by' => $leader->getKey(), 'ended_at' => null, 'deleted_at' => null,
                ]);
            }
        }
        $programNames = ['Pemalang Youth Digital Empowerment 2026', 'Creative Youth Development Program', 'Program Pengembangan Relawan Muda', 'Pemuda Kreatif Kecamatan Taman', 'Laboratorium Desain Pemuda', 'Pemalang Berani Bicara', 'Digital Entrepreneurship for Youth', 'Pemalang dalam Lensa Pemuda', 'Relawan Siaga Pemalang', 'Pemuda Sehat dan Aktif', 'Literasi Pemuda Petarukan', 'Inovasi Digital Comal', 'Pelestarian Seni Muda Belik', 'Jembatan Karier Pemuda', 'Pesisir Bersih Berdaya'];
        $programs = [];
        foreach ($programNames as $i => $name) {
            $org = $organizations[$i];
            $leader = $users[$i % 19];
            $status = $i === 14 ? 'cancelled' : ($i >= 11 ? 'completed' : ($i >= 7 ? 'planned' : 'running'));
            $start = $status === 'completed' ? now()->subMonths(5) : ($status === 'planned' ? now()->addMonth() : now()->subMonths(2));
            $end = $status === 'completed' ? now()->subMonth() : now()->addMonths(3);
            $program = Program::withTrashed()->updateOrCreate(['slug' => Str::slug($name)], [
                'title' => $name, 'organization_id' => $org->getKey(), 'created_by_user_id' => $leader->getKey(),
                'category_id' => ProgramCategory::where('slug', $i === 9 ? 'olahraga-masyarakat' : 'pengembangan-kapasitas-pemuda')->firstOrFail()->getKey(),
                'description' => 'Rangkaian pengembangan kapasitas melalui kelas, praktik, dan pendampingan bersama '.$org->name.'.',
                'objectives' => 'Mendorong keterampilan, kolaborasi, dan karya nyata pemuda.', 'start_date' => $start->toDateString(), 'end_date' => $end->toDateString(), 'execution_status' => $status, 'deleted_at' => null,
            ]);
            $programs[] = $program;
            $path = 'proposals/demo-'.Str::slug($name).'.pdf';
            // A readable, clearly fictional proposal fixture; no real documents.
            if (! Storage::disk('proposal_documents')->exists($path)) {
                Storage::disk('proposal_documents')->put($path, file_get_contents(database_path('fixtures/demo/proposal.pdf')));
            }
            ProgramProposal::withTrashed()->updateOrCreate(['program_id' => $program->getKey(), 'version' => 1], [
                'status' => $status === 'planned' || $status === 'cancelled' ? 'draft' : 'approved', 'requested_budget' => 8000000 + $i * 1500000,
                'proposal_document_path' => $path, 'submitted_at' => $status === 'planned' || $status === 'cancelled' ? null : $start->copy()->subDays(15),
                'reviewed_at' => $status === 'planned' || $status === 'cancelled' ? null : $start->copy()->subDays(10), 'reviewed_by' => $status === 'planned' || $status === 'cancelled' ? null : $verifier->getKey(), 'deleted_at' => null,
            ]);
        }
        $activities = [
            ['Workshop Web Development untuk Pemuda', 'teknologi', 3, 0], ['Pelatihan Public Speaking Dasar', 'pendidikan', 2, 5],
            ['Volunteer Pemalang Youth Festival', 'sosial', 1, 8], ['Seminar Kewirausahaan Digital', 'kewirausahaan', 1, 6], ['Bootcamp Desain Konten Digital', 'kreatif', 4, 4],
            ['Workshop Digital Marketing', 'kewirausahaan', 2, 6], ['Pelatihan Leadership Pemuda', 'pendidikan', 2, 3], ['Turnamen Futsal Pemuda', 'olahraga', 1, 9],
            ['Pelatihan Video Editing', 'kreatif', 2, 1], ['Workshop Personal Branding', 'pendidikan', 1, 13], ['Kelas Fotografi Dasar', 'kreatif', 2, 7],
            ['Pelatihan UMKM Digital', 'kewirausahaan', 2, 6], ['Seminar Karier dan Magang', 'pendidikan', 1, 13], ['Aksi Bersih Lingkungan', 'sosial', 1, 2], ['Pelatihan Content Creator', 'teknologi', 2, 11],
        ];
        foreach ($activities as $i => [$title, $category, $sessionCount, $orgIndex]) {
            $org = $organizations[$orgIndex];
            $manager = $users[$orgIndex % 19];
            // Reuse the existing Passport scenario slugs to avoid duplicate titles.
            $slug = $i === 0 ? Str::slug($title) : ($i < 5 ? 'passport-'.Str::slug($title) : 'demo-'.Str::slug($title));
            $status = $i < 5 ? 'completed' : ($i === 5 ? 'ongoing' : 'scheduled');
            $start = $i < 5 ? now()->startOfDay()->subDays(35 - $i * 5)->setTime(9, 0) : ($i === 5 ? now()->startOfDay()->subDay()->setTime(9, 0) : now()->startOfDay()->addDays(10 + $i * 2)->setTime(9, 0));
            $end = $start->copy()->addDays($sessionCount - 1)->setTime(17, 0);
            if ($i === 5) {
                $end = now()->addDay()->setTime(17, 0);
            }
            $activity = Activity::withTrashed()->updateOrCreate(['slug' => $slug], [
                'organization_id' => $org->getKey(), 'program_id' => $i % 3 === 2 ? null : $programs[$orgIndex]->getKey(), 'category_id' => ActivityCategory::where('slug', $category)->firstOrFail()->getKey(), 'created_by_user_id' => $manager->getKey(),
                'title' => $title, 'description' => 'Belajar '.$category.' melalui praktik dan diskusi. Peserta menghasilkan karya dan menerima umpan balik dari fasilitator '.$org->name.'.',
                'location_type' => 'offline', 'venue_name' => 'Ruang Komunitas '.$org->name, 'administrative_area_id' => $org->administrative_area_id, 'address_text' => 'Lokasi fiktif demonstrasi di tingkat kecamatan',
                'start_at' => $start, 'end_at' => $end, 'registration_open_at' => $start->copy()->subDays(60), 'registration_close_at' => $start->copy()->subDays(2),
                'quota' => $i === 0 ? 60 : 35, 'registration_mode' => 'approval_required', 'min_age' => 16, 'max_age' => 35, 'requires_identity_verification' => true, 'members_only' => false, 'certificate_enabled' => $i !== 4,
                'review_status' => 'approved', 'publication_status' => 'published', 'execution_status' => $status, 'published_at' => $i < 5 ? $start->copy()->subDays(60) : now()->subDays(15)->subHours($i), 'deleted_at' => null,
            ]);
            ActivityReview::withTrashed()->updateOrCreate(['activity_id' => $activity->getKey(), 'decision' => 'approved'], ['reviewer_id' => $verifier->getKey(), 'review_notes' => 'Rencana dan jadwal kegiatan telah ditinjau untuk demo.', 'reviewed_at' => $i < 5 ? $start->copy()->subDays(61) : now()->subDays(17), 'deleted_at' => null]);
            $sessions = [];
            for ($n = 1; $n <= $sessionCount; $n++) {
                $time = $start->copy()->addDays($n - 1);
                $name = $i === 0 ? ['Pengenalan Web Development', 'Praktik HTML, CSS, dan JavaScript', 'Mini Project & Presentasi'][$n - 1] : ['Pengenalan dan Diskusi', 'Praktik Terarah', 'Pengembangan Karya', 'Presentasi dan Evaluasi'][$n - 1];
                $sessions[] = ActivitySession::withTrashed()->updateOrCreate(['activity_id' => $activity->getKey(), 'session_number' => $n], ['title' => $name, 'description' => $title.' — '.$name, 'start_at' => $time, 'end_at' => $time->copy()->addHours(3), 'venue_name' => $activity->venue_name, 'deleted_at' => null]);
            }
            foreach ($users->take($i === 0 ? 20 : 6 + $i % 8) as $j => $youth) {
                // Keep Erik eligible for new Pathway recommendations.
                if ($i >= 6 && $j === 0) {
                    continue;
                }
                $completed = $i < 5 && ($j < 4 || $j % 3 !== 0);
                $participation = ActivityParticipation::withTrashed()->updateOrCreate(['activity_id' => $activity->getKey(), 'user_id' => $youth->getKey()], [
                    'activity_role' => $i === 2 ? 'volunteer' : 'participant', 'registration_status' => 'accepted', 'completion_status' => $completed ? 'completed' : 'pending',
                    'requested_at' => $i < 5 ? $start->copy()->subDays(14) : now()->subDays(4), 'reviewed_at' => $i < 5 ? $start->copy()->subDays(10) : now()->subDays(3), 'reviewed_by' => $manager->getKey(), 'completed_at' => $completed ? $end->copy()->addHour() : null, 'deleted_at' => null,
                ]);
                foreach ($sessions as $n => $session) {
                    if ($session->end_at->isFuture()) {
                        continue;
                    }
                    $attendance = $j < 4 ? 'present' : (($j + $n) % 8 === 0 ? 'excused' : (($j + $n) % 6 === 0 ? 'absent' : 'present'));
                    if ($i === 0) {
                        $absent = [[5, 12], [4], [3, 5, 18]][$n];
                        $excused = [[7, 19], [15], []][$n];
                        $attendance = in_array($j, $absent, true) ? 'absent' : (in_array($j, $excused, true) ? 'excused' : 'present');
                    }
                    if ($i === 4 && $j === 0 && $n === 2) {
                        $attendance = 'absent';
                    }
                    ActivityAttendance::withTrashed()->updateOrCreate(['activity_session_id' => $session->getKey(), 'participation_id' => $participation->getKey()], [
                        'attendance_status' => $attendance, 'checked_in_at' => $attendance === 'present' ? $session->start_at->copy()->addMinutes(3 + $j % 7) : null,
                        'checked_out_at' => $attendance === 'present' ? $session->end_at : null, 'recorded_by' => $manager->getKey(), 'notes' => $attendance === 'excused' ? 'Izin urusan keluarga.' : null, 'deleted_at' => null,
                    ]);
                }
                // A handful of certificates keeps notifications useful rather than noisy.
                if ($completed && $activity->certificate_enabled && $j < 3 && ! UserCertificate::withTrashed()->where('participation_id', $participation->getKey())->exists()) {
                    $certificate = app(IssueActivityCertificateAction::class)->execute($manager, $activity, $participation);
                    $certificate->update(['issued_at' => $end->toDateString()]);
                }
            }
        }
        $workshop = Activity::where('slug', 'workshop-web-development-untuk-pemuda')->firstOrFail();
        $participation = $workshop->participations()->where('user_id', $erik->getKey())->firstOrFail();
        foreach ([
            [$erik, (new ActivityRegistrationReviewed($participation))->toSiporaDatabase($erik)],
            [$users[1], (new CommunityMembershipReviewed($organizations[0], 'accepted'))->toSiporaDatabase($users[1])],
        ] as [$recipient, $payload]) {
            UserNotification::withTrashed()->firstOrCreate(['user_id' => $recipient->getKey(), 'notification_type' => $payload['notification_type'], 'title' => 'Demo — '.$payload['title']], ['body' => $payload['body'], 'data' => $payload['data'] + ['development_fixture' => true], 'read_at' => null]);
        }
        $opportunities = [
            ['Internship Frontend Developer', 'magang'], ['Beasiswa Pemuda Digital', 'beasiswa'], ['Lomba Desain Poster Pemuda', 'kompetisi'], ['Volunteer Festival Pemuda', 'volunteer'], ['Pelatihan UI UX Gratis', 'pelatihan'],
            ['Bootcamp Digital Marketing', 'pelatihan'], ['Kompetisi Business Plan', 'kompetisi'], ['Program Magang UMKM Digital', 'magang'], ['Beasiswa Pendidikan Pemuda', 'beasiswa'], ['Relawan Kampanye Lingkungan', 'volunteer'],
            ['Pelatihan Fotografi Produk', 'pelatihan'], ['Kompetisi Karya Web Pemuda', 'kompetisi'], ['Magang Penulisan Konten', 'magang'], ['Beasiswa Kreativitas Pemalang', 'beasiswa'], ['Relawan Literasi Petarukan', 'volunteer'],
        ];
        foreach ($opportunities as $i => [$title, $category]) {
            $org = $organizations[$i];
            $deadline = $i > 12 ? now()->subDays(10 + $i) : now()->addDays(20 + $i * 3);
            Opportunity::withTrashed()->updateOrCreate(['slug' => $title === 'Volunteer Festival Pemuda' ? 'volunteer-festival-pemuda' : 'demo-'.Str::slug($title)], [
                'title' => $title, 'category_id' => OpportunityCategory::where('slug', $category)->firstOrFail()->getKey(), 'organization_id' => $org->getKey(), 'created_by_user_id' => $users[$i % 19]->getKey(),
                'provider_name' => $org->name, 'description' => 'Kesempatan belajar teknologi, desain, komunikasi, dan kewirausahaan. Siapkan portofolio karya serta alasan mengikuti '.$title.'. Informasi ini fiktif untuk demonstrasi.',
                'administrative_area_id' => $org->administrative_area_id, 'location_text' => 'Pemalang', 'external_url' => 'https://demo.sipora.test/opportunity/'.Str::slug($title),
                'deadline_at' => $deadline, 'starts_at' => $deadline->copy()->addWeek(), 'ends_at' => $deadline->copy()->addMonth(), 'publication_status' => 'published', 'published_at' => now()->subDays(7)->subHours($i), 'deleted_at' => null,
            ]);
        }
    }
}
