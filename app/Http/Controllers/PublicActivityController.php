<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Services\Discovery\ProgramDiscoveryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class PublicActivityController extends Controller
{
    public function show(Request $request, Activity $activity, ProgramDiscoveryService $programDiscovery): View
    {
        $this->ensurePublic($activity);
        $activity->load(['organization', 'category', 'administrativeArea', 'sessions', 'program']);
        $publicProgram = $activity->program && $programDiscovery->isPublic($activity->program) ? $activity->program : null;
        $participation = $request->user()?->activityParticipations()->where('activity_id', $activity->getKey())->first();
        $acceptedCount = $activity->participations()->where('registration_status', 'accepted')->count();

        return view('public.activities.show', compact('activity', 'participation', 'acceptedCount', 'publicProgram'));
    }

    public function poster(Activity $activity): StreamedResponse
    {
        $this->ensurePublic($activity);
        abort_unless($activity->poster_path && Storage::disk('activity_media')->exists($activity->poster_path), 404);
        $mime = Storage::disk('activity_media')->mimeType($activity->poster_path) ?: 'application/octet-stream';

        return response()->stream(function () use ($activity): void {
            $stream = Storage::disk('activity_media')->readStream($activity->poster_path);
            abort_unless(is_resource($stream), 404);
            fpassthru($stream);
            fclose($stream);
        }, 200, ['Content-Type' => $mime, 'Content-Disposition' => 'inline; filename="activity-poster"',
            'Cache-Control' => 'public, max-age=3600', 'X-Content-Type-Options' => 'nosniff']);
    }

    private function ensurePublic(Activity $activity): void
    {
        abort_unless($activity->review_status === Activity::REVIEW_APPROVED
            && $activity->publication_status === Activity::PUBLICATION_PUBLISHED
            && $activity->organization()->where('review_status', 'approved')->where('operational_status', 'active')->exists(), 404);
    }
}
