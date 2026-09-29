<?php

namespace App\Actions\Verifier;

use App\Models\OrganizationMembership;
use App\Models\ProgramProposal;
use App\Models\User;
use App\Notifications\ProgramProposalReviewed;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class ReviewProgramProposalAction
{
    public function execute(User $verifier, ProgramProposal $proposal, string $decision, ?string $notes): ProgramProposal
    {
        return DB::transaction(function () use ($verifier, $proposal, $decision, $notes): ProgramProposal {
            $proposal = ProgramProposal::query()->whereKey($proposal->getKey())->lockForUpdate()->firstOrFail();
            if (! in_array($proposal->status, [ProgramProposal::STATUS_SUBMITTED, ProgramProposal::STATUS_UNDER_REVIEW], true)) {
                throw ValidationException::withMessages(['decision' => ['Proposal tidak lagi dapat ditinjau.']]);
            }
            if (in_array($decision, [ProgramProposal::STATUS_REVISION, ProgramProposal::STATUS_REJECTED], true) && blank($notes)) {
                throw ValidationException::withMessages(['notes' => ['Catatan wajib diisi untuk keputusan ini.']]);
            }
            $managesOrganization = OrganizationMembership::query()
                ->where('organization_id', $proposal->program->organization_id)->where('user_id', $verifier->getKey())
                ->where('membership_status', OrganizationMembership::STATUS_ACTIVE)
                ->whereIn('access_role', [OrganizationMembership::ROLE_LEADER, OrganizationMembership::ROLE_MANAGER])->exists();
            if ($managesOrganization) {
                throw ValidationException::withMessages(['decision' => ['Verifier tidak boleh meninjau Proposal komunitas yang dikelolanya.']]);
            }

            $proposal->forceFill(['status' => $decision, 'reviewed_at' => now(), 'reviewed_by' => $verifier->getKey(), 'review_notes' => filled($notes) ? trim((string) $notes) : null])->save();
            $event = match ($decision) {
                ProgramProposal::STATUS_APPROVED => 'program_proposal_approved',
                ProgramProposal::STATUS_REVISION => 'program_proposal_revision_requested',
                default => 'program_proposal_rejected',
            };
            activity()->causedBy($verifier)->performedOn($proposal)->event($event)
                ->withProperties(['proposal_id' => $proposal->uuid(), 'program_id' => $proposal->program->uuid(), 'version' => $proposal->version, 'decision' => $decision])
                ->log('Keputusan verifikasi Proposal Program');

            $recipients = User::query()->whereHas('organizationMemberships', function ($query) use ($proposal): void {
                $query->where('organization_id', $proposal->program->organization_id)
                    ->where('membership_status', OrganizationMembership::STATUS_ACTIVE)
                    ->whereIn('access_role', [OrganizationMembership::ROLE_LEADER, OrganizationMembership::ROLE_MANAGER]);
            })->get();
            foreach ($recipients as $recipient) {
                $recipient->notify(new ProgramProposalReviewed($proposal));
            }

            return $proposal->fresh(['program']);
        });
    }
}
