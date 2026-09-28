<?php

namespace App\Http\Controllers\Youth;

use App\Http\Controllers\Controller;
use App\Models\UserCertificate;
use App\Services\Certificate\CertificatePdfService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

final class ActivityCertificateController extends Controller
{
    public function index(Request $request): View
    {
        $certificates = $request->user()->certificates()
            ->where('source_type', UserCertificate::SOURCE_SIPORA)
            ->with(['participation.activity.organization'])
            ->latest('issued_at')->paginate(12);

        return view('youth.certificates.index', ['certificates' => $certificates]);
    }

    public function show(Request $request, UserCertificate $certificate): View
    {
        $this->ensureOwner($request, $certificate);
        $certificate->load(['participation.activity.organization', 'participation.activity.category']);

        return view('youth.certificates.show', ['certificate' => $certificate]);
    }

    public function download(Request $request, UserCertificate $certificate, CertificatePdfService $pdf): Response
    {
        $this->ensureOwner($request, $certificate);
        abort_unless($certificate->source_type === UserCertificate::SOURCE_SIPORA
            && $certificate->verification_status === UserCertificate::STATUS_VERIFIED, 404);

        return response($pdf->render($certificate), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$certificate->certificate_number.'.pdf"',
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function ensureOwner(Request $request, UserCertificate $certificate): void
    {
        abort_unless($certificate->user_id === $request->user()->getKey(), 404);
    }
}
