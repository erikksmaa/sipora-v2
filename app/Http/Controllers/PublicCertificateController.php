<?php

namespace App\Http\Controllers;

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

        return view('public.certificates.verify', ['certificate' => $certificate]);
    }
}
