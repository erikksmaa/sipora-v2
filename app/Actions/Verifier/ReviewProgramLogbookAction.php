<?php

namespace App\Actions\Verifier;

use App\Models\OrganizationMembership;
use App\Models\ProgramLogbook;
use App\Models\User;
use App\Notifications\ProgramLogbookReviewed;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class ReviewProgramLogbookAction
{
    public function execute(User $verifier, ProgramLogbook $logbook, string $decision, ?string $notes): ProgramLogbook
    {
        return DB::transaction(function () use ($verifier, $logbook, $decision, $notes): ProgramLogbook {
            $logbook = ProgramLogbook::query()->whereKey($logbook->getKey())->lockForUpdate()->firstOrFail();
            if ($logbook->status !== ProgramLogbook::STATUS_SUBMITTED) {
                throw ValidationException::withMessages(['decision' => ['Logbook tidak lagi menunggu review.']]);
            }
            if ($decision === ProgramLogbook::STATUS_REVISION && blank($notes)) {
                throw ValidationException::withMessages(['notes' => ['Catatan revisi wajib diisi.']]);
            }
            $conflict = OrganizationMembership::query()->where('organization_id', $logbook->program->organization_id)->where('user_id', $verifier->getKey())->where('membership_status', OrganizationMembership::STATUS_ACTIVE)->whereIn('access_role', [OrganizationMembership::ROLE_LEADER, OrganizationMembership::ROLE_MANAGER])->exists();
            if ($conflict) {
                throw ValidationException::withMessages(['decision' => ['Verifier tidak boleh meninjau Logbook komunitas yang dikelolanya.']]);
            }
            $logbook->forceFill(['status' => $decision, 'reviewed_at' => now(), 'reviewed_by' => $verifier->getKey(), 'review_notes' => filled($notes) ? trim($notes) : null])->save();
            $event = $decision === ProgramLogbook::STATUS_APPROVED ? 'program_logbook_approved' : 'program_logbook_revision_requested';
            activity()->causedBy($verifier)->performedOn($logbook)->event($event)->withProperties(['logbook_id' => $logbook->uuid(), 'program_id' => $logbook->program->uuid(), 'decision' => $decision])->log('Keputusan review Logbook Program');
            $logbook->creator->notify(new ProgramLogbookReviewed($logbook));

            return $logbook;
        });
    }
}
