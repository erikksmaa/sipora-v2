<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Support\Auth\HomeRoute;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function store(AuthRequest $request): RedirectResponse
    {
        if (! Auth::attempt($request->safe()->only(['email', 'password']), $request->boolean('remember'))) {
            throw ValidationException::withMessages(['email' => __('auth.failed')]);
        }
        if ($request->user()->isInternalAccount()) {
            if (! $request->user()->internal_active) {
                Auth::guard('web')->logout();
                throw ValidationException::withMessages(['email' => 'Akun ini nonaktif. Hubungi Administrator SIPORA.']);
            }
            $request->session()->put('internal_session_version', $request->user()->internal_session_version);
        }
        $request->session()->regenerate();
        if ($request->user()->isInternalAccount() && $request->user()->must_change_password) {
            return to_route('internal.password.edit');
        }

        return redirect()->intended(route(HomeRoute::for($request->user())))->with('status', 'Selamat datang! Anda berhasil masuk ke SIPORA.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('home')->with('status', 'Anda berhasil keluar dari SIPORA.');
    }
}
