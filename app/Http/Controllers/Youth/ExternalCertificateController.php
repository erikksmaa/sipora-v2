<?php

namespace App\Http\Controllers\Youth;

use App\Http\Controllers\Controller;
use App\Models\UserCertificate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ExternalCertificateController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'issuer_name' => ['required', 'string', 'max:180'],
            'issued_at' => ['required', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:issued_at'],
            'certificate_number' => ['nullable', 'string', 'max:120'],
            'evidence' => ['nullable', File::types(['pdf', 'jpg', 'jpeg', 'png'])->max(5 * 1024)],
        ]);
        $file = $request->file('evidence');
        $path = null;
        if ($file) {
            $path = 'certificate/'.$request->user()->uuid().'/'.Str::random(40).'.'.$file->guessExtension();
            if (! Storage::disk('portfolio_evidence')->putFileAs(dirname($path), $file, basename($path))) {
                abort(500, 'Sertifikat gagal disimpan. Silakan coba lagi.');
            }
        }

        try {
            $request->user()->certificates()->create([
                'source_type' => UserCertificate::SOURCE_EXTERNAL,
                'name' => $data['name'], 'issuer_name' => $data['issuer_name'],
                'issued_at' => $data['issued_at'], 'expires_at' => $data['expires_at'] ?? null,
                'certificate_number' => $data['certificate_number'] ?? null,
                'file_path' => $path, 'verification_status' => 'self_reported',
            ]);
        } catch (\Throwable $exception) {
            if ($path) {
                Storage::disk('portfolio_evidence')->delete($path);
            }
            throw $exception;
        }

        return back()->with('status', 'Sertifikat eksternal tersimpan sebagai klaim pribadi.');
    }

    public function file(Request $request, UserCertificate $certificate): StreamedResponse
    {
        $this->ensureOwner($request, $certificate);
        $path = $certificate->file_path;
        abort_unless($path && Storage::disk('portfolio_evidence')->exists($path), 404);
        $mime = Storage::disk('portfolio_evidence')->mimeType($path);
        abort_unless(in_array($mime, ['application/pdf', 'image/jpeg', 'image/png'], true), 404);

        return response()->stream(function () use ($path): void {
            $stream = Storage::disk('portfolio_evidence')->readStream($path);
            if ($stream === false) {
                abort(404);
            }
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="sertifikat-eksternal.'.($mime === 'application/pdf' ? 'pdf' : ($mime === 'image/png' ? 'png' : 'jpg')).'"',
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function destroy(Request $request, UserCertificate $certificate): RedirectResponse
    {
        $this->ensureOwner($request, $certificate);
        $path = $certificate->file_path;
        $certificate->delete();
        if ($path) {
            Storage::disk('portfolio_evidence')->delete($path);
        }

        return back()->with('status', 'Sertifikat eksternal dihapus.');
    }

    private function ensureOwner(Request $request, UserCertificate $certificate): void
    {
        abort_unless($certificate->user_id === $request->user()->getKey()
            && $certificate->source_type === UserCertificate::SOURCE_EXTERNAL, 404);
    }
}
