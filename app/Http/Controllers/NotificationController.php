<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use App\Services\NotificationActionResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class NotificationController extends Controller
{
    public function index(Request $request, NotificationActionResolver $resolver): View
    {
        $notifications = $request->user()->siporaNotifications()->latest()->paginate(20);
        $links = $notifications->getCollection()->mapWithKeys(fn (UserNotification $notification): array => [$notification->uuid() => $resolver->resolve($request->user(), $notification)]);

        return view('notifications.index', compact('notifications', 'links'));
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
}
