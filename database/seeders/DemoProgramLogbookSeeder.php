<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Program;
use App\Models\ProgramLogbook;
use App\Models\ProgramLogbookMedia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

final class DemoProgramLogbookSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local') || DB::connection()->getDatabaseName() !== 'sipora') {
            throw new RuntimeException('Logbook demo hanya untuk database local sipora.');
        }
        $root = public_path('media/demo/07C_PROGRAM_LOGBOOK');
        $manifest = json_decode(file_get_contents($root.'/manifest.json'), true, flags: JSON_THROW_ON_ERROR);
        $program = Program::where('slug', $manifest['program_slug'])->firstOrFail();
        $creator = $program->creator;
        $slugs = [
            'A001' => 'workshop-web-development-untuk-pemuda',
            'A002' => 'passport-pelatihan-public-speaking-dasar',
            'A003' => 'passport-bootcamp-desain-konten-digital',
            'A005' => 'passport-seminar-kewirausahaan-digital',
        ];
        $activities = [];
        foreach ($slugs as $id => $slug) {
            $activities[$id] = Activity::where('slug', $slug)->firstOrFail();
        }
        foreach ($manifest['entries'] as $entry) {
            foreach ($entry['evidence'] as $evidence) {
                if (! is_file($root.'/'.$evidence['file'])) {
                    throw new RuntimeException('Bukti Logbook tidak ditemukan: '.$evidence['file']);
                }
            }
        }
        foreach ($manifest['entries'] as $entry) {
            foreach ($entry['evidence'] as $evidence) {
                Storage::disk('program_logbook_media')->put('logbooks/demo-p001/'.basename($evidence['file']), file_get_contents($root.'/'.$evidence['file']));
            }
        }
        DB::transaction(function () use ($manifest, $activities, $program, $creator): void {
            // The golden program explicitly groups A001, A002, A003 and A005.
            foreach ($activities as $activity) {
                if ($activity->organization_id !== $program->organization_id) {
                    throw new RuntimeException('Golden Activity dan Program harus mempunyai Community owner yang sama.');
                }
                $activity->forceFill(['program_id' => $program->getKey()])->save();
            }
            foreach ($manifest['entries'] as $entry) {
                $existing = $program->logbooks()->whereDate('log_date', $entry['log_date'])->first();
                $preserveApproved = $existing?->status === ProgramLogbook::STATUS_APPROVED
                    && $program->latestEvaluation()->value('decision') === 'approved';
                $summary = $entry['title']."\n\n".$entry['description'];
                if ($entry['participant_count'] !== null) {
                    $summary .= "\n\nPeserta dilaporkan: ".$entry['participant_count'].' orang.';
                }
                $summary .= "\n\nDATA DEMO SIPORA - BUKTI FIKTIF.";
                $logbook = ProgramLogbook::withTrashed()->updateOrCreate([
                    'program_id' => $program->getKey(), 'log_date' => $entry['log_date'],
                ], [
                    'activity_id' => $entry['activity_id'] ? $activities[$entry['activity_id']]->getKey() : null,
                    'created_by_user_id' => $creator->getKey(), 'summary' => $summary,
                    'obstacles' => null, 'solutions' => null, 'progress_percent' => $entry['progress_percent'],
                    'status' => $preserveApproved ? ProgramLogbook::STATUS_APPROVED : ProgramLogbook::STATUS_SUBMITTED,
                    'submitted_at' => $entry['log_date'].' 15:00:00',
                    'reviewed_at' => $preserveApproved ? $existing->reviewed_at : null,
                    'reviewed_by' => $preserveApproved ? $existing->reviewed_by : null,
                    'review_notes' => $preserveApproved ? $existing->review_notes : null, 'deleted_at' => null,
                ]);
                foreach ($entry['evidence'] as $evidence) {
                    ProgramLogbookMedia::withTrashed()->updateOrCreate([
                        'logbook_id' => $logbook->getKey(),
                        'file_path' => 'logbooks/demo-p001/'.basename($evidence['file']),
                    ], ['uploaded_by' => $creator->getKey(), 'caption' => $evidence['caption'], 'deleted_at' => null]);
                }
            }
        });
        $this->command?->info('P001: 6 Logbook submitted, 7 foto, progres 10/25/45/65/85/100%.');
    }
}
