<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use App\Services\Youth\YouthPortfolioService;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class PublicYouthPortfolioController extends Controller
{
    public function show(string $profile, YouthPortfolioService $portfolio): View
    {
        $presentation = $portfolio->forPublicSlug($profile);
        abort_unless($presentation, 404);

        return view('portfolio.show', ['portfolio' => $presentation]);
    }

    public function photo(string $profile): StreamedResponse
    {
        $profile = UserProfile::query()->where('public_slug', $profile)->with('user.profileVisibility')->firstOrFail();
        abort_unless($profile->user->profileVisibility?->is_profile_public
            && $profile->user->profileVisibility?->show_photo
            && $profile->profile_photo_path
            && Storage::disk('public')->exists($profile->profile_photo_path), 404);

        $path = $profile->profile_photo_path;
        $mime = Storage::disk('public')->mimeType($path) ?: 'application/octet-stream';

        return response()->stream(function () use ($path): void {
            $stream = Storage::disk('public')->readStream($path);
            abort_unless(is_resource($stream), 404);
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="profile-photo"',
            'Cache-Control' => 'public, max-age=3600',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
