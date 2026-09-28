<?php

namespace App\Actions\Youth;

use App\Models\Organization;
use App\Models\OrganizationVerificationRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class SubmitCommunityForReviewAction
{
    public function execute(User $user, Organization $organization, ?string $notes): OrganizationVerificationRequest
    {
        return DB::transaction(function () use ($user, $organization, $notes): OrganizationVerificationRequest {
            $locked = Organization::query()->lockForUpdate()->findOrFail($organization->getKey());

            if (! is_string($locked->created_by_user_id) || ! hash_equals($user->getKey(), $locked->created_by_user_id)) {
                abort(403);
            }

            if (! in_array($locked->review_status, [Organization::REVIEW_DRAFT, Organization::REVIEW_REVISION], true)) {
                throw ValidationException::withMessages(['community' => ['Pengajuan ini tidak dapat dikirim dari status saat ini.']]);
            }

            if ($locked->verificationRequests()->where('status', 'pending')->exists()) {
                throw ValidationException::withMessages(['community' => ['Pengajuan ini sudah menunggu peninjauan.']]);
            }

            $locked->review_status = Organization::REVIEW_PENDING;
            $locked->operational_status = Organization::OPERATIONAL_INACTIVE;
            $locked->save();

            $request = new OrganizationVerificationRequest;
            $request->organization_id = $locked->getKey();
            $request->submitted_by = $user->getKey();
            $request->status = 'pending';
            $request->submission_notes = $notes;
            $request->submitted_at = now();
            $request->save();

            activity()
                ->causedBy($user)
                ->performedOn($locked)
                ->event('community_submitted')
                ->withProperties(['organization_id' => $locked->uuid(), 'submission_id' => $request->uuid()])
                ->log('Pengajuan verifikasi komunitas');

            return $request;
        });
    }
}
