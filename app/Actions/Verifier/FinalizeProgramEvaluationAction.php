<?php

namespace App\Actions\Verifier;

use App\Models\OrganizationMembership;
use App\Models\Program;
use App\Models\ProgramEvaluation;
use App\Models\User;
use App\Notifications\ProgramEvaluationFinalized;
use App\Services\ProgramEvaluationEligibilityService;
use App\Support\BinaryUuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

final class FinalizeProgramEvaluationAction
{
    public function __construct(private readonly ProgramEvaluationEligibilityService $eligibility) {}

    public function execute(User $verifier, Program $program, string $decision, ?string $notes, ?string $expectedLatestEvaluationId): ProgramEvaluation
    {
        return DB::transaction(function () use ($verifier, $program, $decision, $notes, $expectedLatestEvaluationId): ProgramEvaluation {
            $program = Program::query()->whereKey($program->getKey())->lockForUpdate()->firstOrFail();
            $assessment = $this->eligibility->assess($program);
            if (! $assessment['eligible']) {
                throw ValidationException::withMessages(['evaluation' => $assessment['reasons']]);
            }

            $latest = $program->evaluations()->lockForUpdate()->first();
            try {
                $expected = filled($expectedLatestEvaluationId) ? BinaryUuid::bytes($expectedLatestEvaluationId) : null;
            } catch (InvalidArgumentException) {
                $expected = null;
            }
            if ($latest?->getKey() !== $expected) {
                throw ValidationException::withMessages(['evaluation' => ['Halaman evaluasi sudah kedaluwarsa. Muat ulang sebelum menyimpan keputusan.']]);
            }

            $evaluation = ProgramEvaluation::create(['program_id' => $program->getKey(), 'verifier_id' => $verifier->getKey(), 'decision' => $decision,
                'evaluation_notes' => filled($notes) ? trim($notes) : null, 'evaluated_at' => now()]);
            $properties = ['program_id' => $program->uuid(), 'evaluation_id' => $evaluation->uuid(), 'decision' => $decision];
            activity()->causedBy($verifier)->performedOn($evaluation)->event('program_evaluation_created')->withProperties($properties)->log('Evaluasi akhir Program dibuat');
            activity()->causedBy($verifier)->performedOn($evaluation)->event('program_evaluation_finalized')->withProperties($properties)->log('Evaluasi akhir Program difinalkan');

            if ($decision === ProgramEvaluation::DECISION_APPROVED) {
                $program->forceFill(['execution_status' => Program::STATUS_COMPLETED])->save();
                activity()->causedBy($verifier)->performedOn($program)->event('program_completed')->withProperties($properties)->log('Program diselesaikan');
            }

            OrganizationMembership::query()->with('user')->where('organization_id', $program->organization_id)
                ->where('membership_status', OrganizationMembership::STATUS_ACTIVE)
                ->whereIn('access_role', [OrganizationMembership::ROLE_LEADER, OrganizationMembership::ROLE_MANAGER])->get()
                ->pluck('user')->filter()->unique(fn (User $user) => $user->uuid())->each(fn (User $user) => $user->notify(new ProgramEvaluationFinalized($evaluation)));

            return $evaluation;
        });
    }
}
