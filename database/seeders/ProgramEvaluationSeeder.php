<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\ProgramEvaluation;
use App\Models\User;
use Illuminate\Database\Seeder;

final class ProgramEvaluationSeeder extends Seeder
{
    public function run(): void
    {
        $program = Program::query()->where('slug', 'program-tuntas-pemuda')->firstOrFail();
        $verifier = User::query()->where('email', 'verifier@sipora.test')->firstOrFail();
        ProgramEvaluation::withTrashed()->updateOrCreate(['program_id' => $program->getKey(), 'verifier_id' => $verifier->getKey(), 'decision' => ProgramEvaluation::DECISION_APPROVED],
            ['evaluation_notes' => 'Seluruh bukti administratif telah ditinjau dan Program dinyatakan selesai.', 'evaluated_at' => now()->subWeeks(2), 'deleted_at' => null]);
        $program->forceFill(['execution_status' => Program::STATUS_COMPLETED])->save();
    }
}
