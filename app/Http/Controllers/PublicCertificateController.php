<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Organization;
use App\Models\UserCertificate;
use Illuminate\View\View;

final class PublicCertificateController extends Controller
{
    public function __invoke(string $code): View
    {
        $certificate = UserCertificate::query()
            ->where('verification_code', $code)
            ->where('source_type', UserCertificate::SOURCE_SIPORA)
            ->where('verification_status', UserCertificate::STATUS_VERIFIED)
            ->with(['user.profile', 'participation.activity.organization'])
            ->first();

        $activity = $certificate?->participation?->activity;
        $organization = $activity?->organization;
        $activityUrl = $activity
            && $activity->review_status === Activity::REVIEW_APPROVED
            && $activity->publication_status === Activity::PUBLICATION_PUBLISHED
            && $organization?->review_status === Organization::REVIEW_APPROVED
            && $organization?->operational_status === Organization::OPERATIONAL_ACTIVE
                ? route('activities.show', $activity) : null;
        $communityUrl = $organization?->review_status === Organization::REVIEW_APPROVED
            && $organization?->operational_status === Organization::OPERATIONAL_ACTIVE
                ? route('communities.show', $organization) : null;

        return view('public.certificates.verify', compact('certificate', 'activityUrl', 'communityUrl'));
    }
}
