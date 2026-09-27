<?php

namespace App\Http\Controllers\Youth;

use App\Actions\Youth\SubmitIdentityVerificationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Youth\SubmitIdentityVerificationRequest;
use App\Support\BinaryUuid;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IdentityVerificationController extends Controller
{
    public function __invoke(Request $request): View
    {
        return $this->show($request);
    }

    public function show(Request $request): View
    {
        $user = $request->user()->loadMissing(['profile', 'identity.verifications']);
        $identity = $user->identity;
        if (! $identity) {
            $identity = $user->identity()->create([
                'id' => BinaryUuid::generate(),
                'verification_status' => 'unverified',
            ]);
        }

        return view('youth.identity-verification', [
            'user' => $user,
            'identity' => $identity,
            'latestVerification' => $identity->latestVerification,
        ]);
    }

    public function store(SubmitIdentityVerificationRequest $request, SubmitIdentityVerificationAction $action): RedirectResponse
    {
        $action->execute(
            $request->user(),
            $request->safe()->only(['document_type', 'document_number']),
            $request->file('document_file')
        );

        return back()->with('status', 'Dokumen identitas berhasil diajukan dan sedang menunggu proses verifikasi.');
    }
}
