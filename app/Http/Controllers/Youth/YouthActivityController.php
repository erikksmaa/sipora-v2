<?php

namespace App\Http\Controllers\Youth;

use App\Http\Controllers\Controller;
use App\Models\ActivityParticipation;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class YouthActivityController extends Controller
{
    public function __invoke(Request $request): View
    {
        $status = $request->string('status')->toString();
        $allowed = ['pending', 'accepted', 'completed', 'cancelled'];
        abort_unless($status === '' || in_array($status, $allowed, true), 404);

        $query = $request->user()->activityParticipations()
            ->with(['activity.organization', 'activity.category', 'certificate'])
            ->latest('requested_at');

        match ($status) {
            'pending' => $query->where('registration_status', ActivityParticipation::REGISTRATION_PENDING),
            'accepted' => $query->where('registration_status', ActivityParticipation::REGISTRATION_ACCEPTED)
                ->where('completion_status', ActivityParticipation::COMPLETION_PENDING),
            'completed' => $query->where('completion_status', ActivityParticipation::COMPLETION_COMPLETED),
            'cancelled' => $query->where('registration_status', ActivityParticipation::REGISTRATION_CANCELLED),
            default => null,
        };

        return view('youth.activities.index', [
            'participations' => $query->paginate(12)->withQueryString(),
            'status' => $status,
        ]);
    }
}
