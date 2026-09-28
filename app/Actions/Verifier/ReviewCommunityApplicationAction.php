<?php

namespace App\Actions\Verifier;

use App\Models\Organization;
use App\Models\OrganizationMembership;
use App\Models\OrganizationVerificationRequest;
use App\Models\User;
use App\Notifications\CommunityVerificationReviewed;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class ReviewCommunityApplicationAction
{
    public function execute(User $verifier, Organization $organization, string $decision, ?string $notes): Organization
    {
        if (! in_array($decision, [Organization::REVIEW_APPROVED, Organization::REVIEW_REVISION, Organization::REVIEW_REJECTED], true)) {
            throw ValidationException::withMessages(['decision' => ['Keputusan verifikasi tidak valid.']]);
        }

        if (in_array($decision, [Organization::REVIEW_REVISION, Organization::REVIEW_REJECTED], true) && blank($notes)) {
            throw ValidationException::withMessages(['review_notes' => ['Catatan wajib diisi untuk keputusan ini.']]);
        }

        return DB::transaction(function () use ($verifier, $organization, $decision, $notes): Organization {
            $organization = Organization::query()->whereKey($organization->getKey())->lockForUpdate()->firstOrFail();

            if ($organization->review_status !== Organization::REVIEW_PENDING) {
                throw ValidationException::withMessages([
                    'decision' => ['Pengajuan tidak lagi menunggu verifikasi. Muat ulang antrean.'],
                ]);
            }

            $submission = $organization->verificationRequests()
                ->where('status', OrganizationVerificationRequest::STATUS_PENDING)
                ->latest('submitted_at')
                ->lockForUpdate()
                ->first();

            if (! $submission) {
                throw ValidationException::withMessages([
                    'decision' => ['Pengajuan aktif tidak ditemukan. Muat ulang antrean.'],
                ]);
            }

            $notes = filled($notes) ? trim((string) $notes) : null;
            $submission->forceFill([
                'status' => $decision,
                'reviewed_by' => $verifier->getKey(),
                'reviewed_at' => now(),
                'review_notes' => $notes,
            ])->save();

            $organization->review_status = $decision;
            $organization->operational_status = $decision === Organization::REVIEW_APPROVED
                ? Organization::OPERATIONAL_ACTIVE
                : Organization::OPERATIONAL_INACTIVE;
            $organization->approved_at = $decision === Organization::REVIEW_APPROVED ? now() : null;
            $organization->approved_by = $decision === Organization::REVIEW_APPROVED ? $verifier->getKey() : null;
            $organization->save();

            if ($decision === Organization::REVIEW_APPROVED) {
                OrganizationMembership::create([
                    'organization_id' => $organization->getKey(),
                    'user_id' => $submission->submitted_by,
                    'access_role' => OrganizationMembership::ROLE_LEADER,
                    'position_title' => 'Pendiri',
                    'membership_status' => OrganizationMembership::STATUS_ACTIVE,
                    'requested_at' => $submission->submitted_at,
                    'approved_at' => now(),
                    'approved_by' => $verifier->getKey(),
                ]);
            }

            $event = match ($decision) {
                Organization::REVIEW_APPROVED => 'community_approved',
                Organization::REVIEW_REVISION => 'community_revision_requested',
                default => 'community_rejected',
            };

            activity()
                ->causedBy($verifier)
                ->performedOn($submission)
                ->event($event)
                ->withProperties([
                    'organization_id' => $organization->uuid(),
                    'submission_id' => $submission->uuid(),
                    'decision' => $decision,
                ])
                ->log('Keputusan verifikasi komunitas');

            $submission->submitter->notify(new CommunityVerificationReviewed($organization, $decision));

            return $organization->fresh(['creator', 'category', 'latestVerificationRequest']);
        });
    }
}
