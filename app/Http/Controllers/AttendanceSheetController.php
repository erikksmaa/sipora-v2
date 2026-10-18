<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityParticipation;
use App\Models\Organization;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

final class AttendanceSheetController extends Controller
{
    public function activity(Request $request, Organization $organization, Activity $activity)
    {
        abort_unless($activity->organization_id === $organization->getKey(), 404);
        Gate::authorize('view', $activity);

        return $this->sheet($request, $activity->participations()->where('registration_status', ActivityParticipation::REGISTRATION_ACCEPTED), [
            'heading' => 'DAFTAR HADIR KEGIATAN',
            'title' => $activity->title,
            'organizer' => $organization->name,
            'date' => $activity->start_at?->timezone('Asia/Jakarta')->translatedFormat('d F Y'),
            'venue' => $activity->venue_name,
        ]);
    }

    public function managerProgram(Request $request, Organization $organization, Program $program)
    {
        abort_unless($program->organization_id === $organization->getKey(), 404);

        return $this->program($request, $program);
    }

    public function program(Request $request, Program $program)
    {
        Gate::authorize('manageApplications', $program);

        return $this->sheet($request, $program->participants(), [
            'heading' => 'DAFTAR HADIR PESERTA PROGRAM',
            'title' => $program->title,
            'organizer' => $program->organization->name,
            'date' => collect([$program->start_date?->translatedFormat('d F Y'), $program->end_date?->translatedFormat('d F Y')])->filter()->unique()->implode(' – '),
            'venue' => null,
        ]);
    }

    private function sheet(Request $request, $query, array $metadata)
    {
        $includePhone = $request->boolean('include_phone');
        // Project only the printable fields; never pass identity or account data to the view.
        $rows = $query->whereHas('user')->with(['user:id,name', 'user.profile' => fn ($q) => $q->select($includePhone ? ['id', 'user_id', 'full_name', 'phone'] : ['id', 'user_id', 'full_name'])])->get()
            ->map(fn ($entry) => ['name' => $entry->user->profile?->full_name ?: $entry->user->name, 'phone' => $includePhone ? $entry->user->profile?->phone : null, 'key' => $entry->user->uuid()])
            ->sort(fn ($a, $b) => strnatcasecmp($a['name'], $b['name']) ?: strcmp($a['key'], $b['key']))->values();

        return response()->view('print.attendance-sheet', [...$metadata, 'rows' => $rows, 'includePhone' => $includePhone])
            ->header('Cache-Control', 'private, no-store')->header('X-Robots-Tag', 'noindex, nofollow');
    }
}
