<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\UserNotification;
use App\Services\Community\ManagedCommunityService;
use App\Services\NotificationActionResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final class NotificationController extends Controller
{
    public function index(Request $request, NotificationActionResolver $resolver): View
    {
        return view('notifications.index', $this->listing($request, $resolver));
    }

    public function managerIndex(Request $request, Organization $organization, ManagedCommunityService $managed, NotificationActionResolver $resolver): View
    {
        Gate::authorize('manageMemberships', $organization);
        $listing = $this->listing($request, $resolver);
        $prefix = route('manager.dashboard', $organization);
        $listing['links'] = $listing['links']->map(fn (?string $link) => $link && ($link === $prefix || str_starts_with($link, $prefix.'/')) ? $link : null);

        return view('notifications.index', [...$listing,
            'organization' => $organization,
            'managedCommunities' => $managed->forUser($request->user()),
        ]);
    }

    public function read(Request $request, UserNotification $notification): RedirectResponse
    {
        abort_unless(hash_equals($notification->user_id, $request->user()->getKey()), 404);
        if ($notification->read_at === null) {
            $notification->forceFill(['read_at' => now()])->save();
        }

        return back()->with('status', 'Notifikasi ditandai telah dibaca.');
    }

    public function readAll(Request $request): RedirectResponse
    {
        $request->user()->siporaNotifications()->whereNull('read_at')->update(['read_at' => now()]);

        return back()->with('status', 'Semua notifikasi ditandai telah dibaca.');
    }

    private function listing(Request $request, NotificationActionResolver $resolver): array
    {
        $notifications = $request->user()->siporaNotifications()->latest()->paginate(20);
        $links = $notifications->getCollection()->mapWithKeys(fn (UserNotification $notification): array => [$notification->uuid() => $resolver->resolve($request->user(), $notification)]);

        return compact('notifications', 'links');
    }
}
