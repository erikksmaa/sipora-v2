<?php

namespace Database\Seeders;

use App\Models\{ActivityParticipation, ForumReply, ForumThread, Organization, OrganizationMembership, Program, SocialComment, SocialFollow, SocialPost, User, UserCertificate, UserNotification};
use App\Notifications\{ActivityCertificateIssued, ActivityRegistrationReviewed, CommunityMembershipReviewed, FinancialReportReviewed, ForumThreadReplied, ProgramProposalReviewed, SocialFeedNotification};
use App\Services\NotificationActionResolver;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class DemoNotificationSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local') || DB::connection()->getDatabaseName() !== 'sipora') {
            throw new RuntimeException('Batch 8F hanya untuk database lokal sipora.');
        }
        $erik = User::where('email', 'youth1@sipora.test')->firstOrFail();
        $aulia = User::where('email', 'asset.y002@sipora.test')->firstOrFail();
        $community = Organization::where('slug', 'komunitas-pemuda-digital-pemalang')->firstOrFail();
        $participation = ActivityParticipation::where('user_id', $erik->getKey())
            ->where('registration_status', ActivityParticipation::REGISTRATION_ACCEPTED)
            ->whereHas('activity', fn ($q) => $q->where('slug', 'workshop-web-development-untuk-pemuda'))
            ->with('activity')->firstOrFail();
        $certificate = UserCertificate::where('user_id', $erik->getKey())
            ->where('participation_id', $participation->getKey())
            ->where('source_type', UserCertificate::SOURCE_SIPORA)->where('verification_status', UserCertificate::STATUS_VERIFIED)
            ->with('participation.activity')->firstOrFail();
        $membership = OrganizationMembership::where('user_id', $erik->getKey())->where('organization_id', $community->getKey())
            ->where('membership_status', OrganizationMembership::STATUS_ACTIVE)->firstOrFail();
        $p001 = Program::where('title', 'Pemalang Youth Digital Empowerment 2026')->with('latestFinancialReport.program')->firstOrFail();
        $p002 = Program::where('title', 'Creative Youth Development Program')->with(['latestProposal.program', 'organization.creator'])->firstOrFail();
        if ($p001->latestFinancialReport?->status !== 'approved' || $p002->latestProposal?->status !== 'revision') {
            throw new RuntimeException('Batch 7G belum siap: P001 E-LPJ approved dan P002 proposal revision wajib tersedia.');
        }
        $manager = $p002->organization->creator;
        $thread = ForumThread::where('author_id', $erik->getKey())->where('status', ForumThread::ACTIVE)
            ->whereHas('replies', fn ($q) => $q->where('is_hidden', false)->where('author_id', '!=', $erik->getKey()))
            ->where('title', 'Mulai belajar Laravel atau JavaScript dulu?')->firstOrFail();
        $reply = ForumReply::where('thread_id', $thread->getKey())->where('is_hidden', false)
            ->where('author_id', '!=', $erik->getKey())->firstOrFail();
        $feed = json_decode(file_get_contents(public_path('media/demo/08A_SOCIAL_FEED/manifest.json')), true, flags: JSON_THROW_ON_ERROR);
        $entry = collect($feed['media'])->firstWhere('id', 'FEED011');
        $post = SocialPost::published()->where('author_id', $erik->getKey())->whereNull('organization_id')
            ->where('body', $entry['caption']."\n\nDATA DEMO SIPORA")->firstOrFail();

        $mapping = DB::transaction(function () use ($erik, $aulia, $community, $participation, $certificate, $membership, $p001, $p002, $manager, $thread, $reply, $post): array {
            // Local fictional interactions provide real backing records. No notifier,
            // email, broadcast, or queue is invoked by this fixture.
            $comment = SocialComment::firstOrCreate([
                'post_id' => $post->getKey(), 'author_id' => $aulia->getKey(),
                'body' => 'Menarik melihat proses belajarnya. Kalau sudah siap, boleh berbagi mini project supaya kita bisa saling memberi masukan. [DATA DEMO SIPORA]',
            ], ['is_hidden' => false]);
            if ($comment->is_hidden) {
                throw new RuntimeException('Komentar demo disembunyikan; event komentar tidak dapat digunakan.');
            }
            $follow = SocialFollow::firstOrCreate(['user_id' => $aulia->getKey(), 'youth_id' => $erik->getKey(), 'organization_id' => null]);
            $events = [
                ['N001', $erik, new ActivityRegistrationReviewed($participation), 'Pendaftaran Activity diterima', 'Pendaftaran Anda untuk Workshop Web Development untuk Pemuda telah diterima.', ['participation_id' => $participation->uuid()], false, 180],
                ['N002', $erik, new CommunityMembershipReviewed($community, 'accepted'), 'Permintaan bergabung Community disetujui', 'Anda telah bergabung dengan Komunitas Pemuda Digital Pemalang. Keanggotaan Anda saat ini aktif.', ['membership_id' => $membership->uuid()], true, 150],
                ['N003', $erik, new ActivityCertificateIssued($certificate), 'Sertifikat tersedia', 'Sertifikat Workshop Web Development untuk Pemuda sudah tersedia di Portfolio Anda.', [], false, 120],
                ['N004', $manager, new ProgramProposalReviewed($p002->latestProposal), 'Proposal perlu revisi', 'Proposal Creative Youth Development Program perlu revisi. Mohon lengkapi indikator keberhasilan program dan rincian rencana anggaran kegiatan.', [], false, 95],
                ['N005', $erik, new FinancialReportReviewed($p001->latestFinancialReport), 'E-LPJ disetujui', 'E-LPJ Pemalang Youth Digital Empowerment 2026 telah disetujui. Program P001 telah selesai.', [], false, 75],
                ['N006', $erik, new ForumThreadReplied($thread), 'Thread mendapat balasan', 'Diskusi “'.$thread->title.'” mendapat balasan. Buka thread untuk melanjutkan percakapan.', ['reply_id' => $reply->uuid()], true, 45],
                ['N007', $erik, new SocialFeedNotification('social_commented', '', $post->uuid()), 'Post mendapat komentar', 'Aulia Rahma Putri memberi komentar pada post latihan pengembangan web Anda.', ['comment_id' => $comment->uuid(), 'actor_id' => $aulia->uuid()], false, 20],
                ['N008', $erik, new SocialFeedNotification('social_followed', ''), 'Pengguna baru mengikuti akun Anda', 'Aulia Rahma Putri mulai mengikuti update publik Anda.', ['follow_id' => $follow->uuid(), 'actor_id' => $aulia->uuid()], false, 10],
            ];
            $result = [];
            foreach ($events as [$eventId, $recipient, $notification, $title, $body, $extra, $read, $minutes]) {
                $payload = $notification->toSiporaDatabase($recipient);
                $data = array_merge($payload['data'], $extra, ['demo_batch' => '8F', 'demo_event_id' => $eventId, 'fictional' => true, 'timeline' => 'synthetic notification presentation time']);
                // Respect read state and user deletions on rerun; stable event IDs
                // prevent duplicate notifications without mutating prior workflows.
                $row = UserNotification::withTrashed()->where('user_id', $recipient->getKey())->where('data->demo_batch', '8F')->where('data->demo_event_id', $eventId)->first();
                if (! $row) {
                    $at = now()->subMinutes($minutes);
                    $row = UserNotification::create(['user_id' => $recipient->getKey(), 'notification_type' => $payload['notification_type'],
                        'title' => $title, 'body' => $body."\n\nDATA DEMO SIPORA", 'data' => $data, 'read_at' => $read ? $at->copy()->addMinutes(5) : null]);
                    $row->forceFill(['created_at' => $at, 'updated_at' => $at])->save();
                }
                $link = app(NotificationActionResolver::class)->resolve($recipient, $row);
                if (! $link) {
                    throw new RuntimeException('Tidak ada link yang dapat diakses penerima untuk '.$eventId);
                }
                $result[] = ['event_id' => $eventId, 'notification_uuid' => $row->uuid(), 'recipient' => $recipient->name,
                    'recipient_email' => $recipient->email, 'type' => $row->notification_type, 'title' => $row->title,
                    'body' => $row->body, 'data' => $row->data, 'read' => $row->read_at !== null, 'deleted' => $row->trashed(),
                    'created_at' => $row->created_at->toIso8601String(), 'action_url' => $link];
            }
            return $result;
        });
        $dir = public_path('media/demo/08F_NOTIFICATIONS');
        if (! is_dir($dir)) mkdir($dir, 0777, true);
        file_put_contents($dir.'/notification-mapping.json', json_encode(['batch' => '8F', 'fictional' => true, 'notification_count' => 8,
            'database_seeded' => true, 'external_delivery' => false, 'notifications' => $mapping], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $this->command?->info('Batch 8F: 8 notifikasi lokal; 7 untuk Erik dan 1 untuk pengelola P002.');
    }
}
