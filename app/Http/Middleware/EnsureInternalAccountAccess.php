<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class EnsureInternalAccountAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || ! $user->isInternalAccount()) {
            return $next($request);
        }
        $user->refresh();
        $version = $request->session()->get('internal_session_version');
        if (! $user->internal_active || ($version === null && $user->internal_session_version > 0) || ($version !== null && (int) $version !== $user->internal_session_version)) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return to_route('login')->withErrors(['email' => 'Akun nonaktif atau sesi telah berakhir. Silakan masuk kembali atau hubungi Administrator.']);
        }
        $request->session()->put('internal_session_version', $user->internal_session_version);
        if ($user->must_change_password && ! $request->routeIs('internal.password.*', 'logout')) {
            return to_route('internal.password.edit');
        }

        return $next($request);
    }
}
