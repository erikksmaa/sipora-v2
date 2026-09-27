<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\ReviewIdentityVerificationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReviewIdentityVerificationRequest;
use App\Models\UserIdentityVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class IdentityVerificationController extends Controller
{
    public function index(Request $request): View
    {
        $validated = validator($request->query(), [
            'status' => ['nullable', Rule::in(['all', 'pending', 'revision', 'rejected', 'verified'])],
            'search' => ['nullable', 'string', 'max:120'],
        ])->validate();
        $status = $validated['status'] ?? 'pending';
        $search = trim($validated['search'] ?? '');

        $submissions = UserIdentityVerification::query()
            ->with(['userIdentity.user.profile'])
            ->when($status !== 'all', fn ($query) => $query->where('status', $status))
            ->when($search !== '', fn ($query) => $query->whereHas('userIdentity.user', function ($users) use ($search): void {
                $users->where(function ($match) use ($search): void {
                    $match->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }))
            ->latest('submitted_at')
            ->paginate(15)
            ->withQueryString();

        $counts = UserIdentityVerification::query()
            ->selectRaw('status, COUNT(*) AS total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.identity-verifications.index', compact('submissions', 'counts', 'status', 'search'));
    }

    public function show(UserIdentityVerification $submission): View
    {
        $submission->load(['userIdentity.user.profile', 'userIdentity.user.primaryDomicile.administrativeArea', 'reviewer']);
        $previousReview = $submission->userIdentity->verifications()
            ->where('id', '!=', $submission->getKey())
            ->whereIn('status', [
                UserIdentityVerification::STATUS_REVISION,
                UserIdentityVerification::STATUS_REJECTED,
            ])
            ->whereNotNull('review_notes')
            ->latest('reviewed_at')
            ->first();

        return view('admin.identity-verifications.show', [
            'submission' => $submission,
            'documentNumber' => $submission->documentNumber(),
            'previousReview' => $previousReview,
        ]);
    }

    public function document(UserIdentityVerification $submission): StreamedResponse
    {
        abort_unless(Storage::disk('private')->exists($submission->document_path), 404);
        $mime = Storage::disk('private')->mimeType($submission->document_path) ?: 'application/octet-stream';
        $extension = match ($mime) {
            'application/pdf' => 'pdf',
            'image/png' => 'png',
            default => 'jpg',
        };

        return response()->stream(function () use ($submission): void {
            $stream = Storage::disk('private')->readStream($submission->document_path);
            abort_unless(is_resource($stream), 404);
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="identity-document.'.$extension.'"',
            'Cache-Control' => 'private, no-store, max-age=0',
            'Pragma' => 'no-cache',
            'X-Content-Type-Options' => 'nosniff',
            'Content-Security-Policy' => "default-src 'none'; frame-ancestors 'self'",
        ]);
    }

    public function review(
        ReviewIdentityVerificationRequest $request,
        UserIdentityVerification $submission,
        ReviewIdentityVerificationAction $action,
    ): RedirectResponse {
        $action->execute(
            $request->user(),
            $submission,
            $request->validated('decision'),
            $request->validated('review_notes'),
        );

        return to_route('admin.identity-verifications.show', $submission)
            ->with('status', 'Keputusan verifikasi identitas berhasil disimpan.');
    }
}
