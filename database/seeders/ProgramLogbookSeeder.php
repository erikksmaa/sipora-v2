<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\ProgramLogbook;
use App\Models\ProgramLogbookMedia;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProgramLogbookSeeder extends Seeder
{
    public function run(): void
    {
        $program = Program::query()->where('slug', 'program-pemuda-digital-2026')->firstOrFail();
        $manager = User::query()->where('email', 'youth1@sipora.test')->firstOrFail();
        $verifier = User::query()->where('email', 'verifier@sipora.test')->firstOrFail();
        $activity = $program->activities()->firstOrFail();
        $records = [
            ['date' => now()->subDays(14)->toDateString(), 'summary' => 'Persiapan pelaksanaan dan koordinasi fasilitator telah selesai.', 'progress' => 25, 'status' => ProgramLogbook::STATUS_APPROVED, 'reviewed' => true],
            ['date' => now()->subDays(4)->toDateString(), 'summary' => 'Activity pertama berjalan sesuai rencana dan dokumentasi telah dihimpun.', 'progress' => 55, 'status' => ProgramLogbook::STATUS_SUBMITTED, 'reviewed' => false],
        ];
        foreach ($records as $index => $record) {
            $logbook = ProgramLogbook::withTrashed()->updateOrCreate(['program_id' => $program->getKey(), 'log_date' => $record['date']], ['activity_id' => $activity->getKey(), 'created_by_user_id' => $manager->getKey(), 'summary' => $record['summary'], 'obstacles' => $index ? 'Penyesuaian jadwal narasumber.' : null, 'solutions' => $index ? 'Koordinasi jadwal pengganti.' : null, 'progress_percent' => $record['progress'], 'status' => $record['status'], 'submitted_at' => now()->subDays(3), 'reviewed_at' => $record['reviewed'] ? now()->subDays(10) : null, 'reviewed_by' => $record['reviewed'] ? $verifier->getKey() : null, 'review_notes' => null, 'deleted_at' => null]);
            if ($index === 1) {
                $path = 'logbooks/development/program-pemuda-digital.pdf';
                Storage::disk('program_logbook_media')->put($path, "%PDF-1.4\n% SIPORA development fixture\n");
                ProgramLogbookMedia::withTrashed()->updateOrCreate(['logbook_id' => $logbook->getKey(), 'file_path' => $path], ['uploaded_by' => $manager->getKey(), 'caption' => 'Dokumentasi pelaksanaan contoh', 'deleted_at' => null]);
            }
        }
    }
}
