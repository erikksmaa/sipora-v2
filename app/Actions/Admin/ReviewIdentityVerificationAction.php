<?php

namespace App\Actions\Admin;

use App\Models\User;
use App\Models\UserIdentityVerification;
use App\Notifications\IdentityVerificationReviewed;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class ReviewIdentityVerificationAction
{
    public function execute(
        User $admin,
        UserIdentityVerification $submission,
        string $decision,
        ?string $notes = null,
    ): UserIdentityVerification {
        return DB::transaction(function () use ($admin, $submission, $decision, $notes): UserIdentityVerification {
            $submission = UserIdentityVerification::query()
                ->whereKey($submission->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($submission->status !== UserIdentityVerification::STATUS_PENDING) {
                throw ValidationException::withMessages([
                    'decision' => ['Pengajuan ini sudah diputuskan dan tidak dapat ditinjau ulang.'],
                ]);
            }

            $identity = $submission->userIdentity()->lockForUpdate()->firstOrFail();
            $latestPending = $identity->verifications()
                ->where('status', UserIdentityVerification::STATUS_PENDING)
                ->latest('submitted_at')
                ->lockForUpdate()
                ->first();

            if (! $latestPending || ! hash_equals($latestPending->getKey(), $submission->getKey())) {
                throw ValidationException::withMessages([
                    'decision' => ['Pengajuan ini bukan pengajuan aktif terbaru. Muat ulang antrean.'],
                ]);
            }

            $notes = filled($notes) ? trim((string) $notes) : null;
            $submission->forceFill([
                'status' => $decision,
                'reviewed_at' => now(),
                'reviewed_by' => $admin->getKey(),
                'review_notes' => $notes,
            ])->save();

            $identity->forceFill([
                'verification_status' => $decision,
                'verification_method' => $submission->document_type,
                'verified_at' => $decision === UserIdentityVerification::STATUS_VERIFIED ? now() : null,
                'verified_by' => $decision === UserIdentityVerification::STATUS_VERIFIED ? $admin->getKey() : null,
            ])->save();

            $event = match ($decision) {
                UserIdentityVerification::STATUS_VERIFIED => 'identity_verified',
                UserIdentityVerification::STATUS_REVISION => 'identity_revision_requested',
                default => 'identity_rejected',
            };

            activity()
                ->causedBy($admin)
                ->performedOn($submission)
                ->event($event)
                ->withProperties([
                    'submission_id' => $submission->uuid(),
                    'decision' => $decision,
                ])
                ->log('Keputusan verifikasi identitas pemuda');

            $identity->user->notify(new IdentityVerificationReviewed($submission, $decision));

            return $submission->fresh(['userIdentity.user', 'reviewer']);
        });
    }
}
