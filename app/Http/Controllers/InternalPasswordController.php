<?php

namespace App\Http\Controllers;

use App\Services\InternalAccountService;
use App\Support\Auth\HomeRoute;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

final class InternalPasswordController extends Controller
{
    public function edit(Request $request)
    {
        $forced = $request->routeIs('internal.password.*');
        if ($forced && ! $request->user()->must_change_password) {
            return to_route($this->workspace($request).'.account.password.edit');
        }

        return response()->view('government.account.password', ['workspace' => $this->workspace($request), 'forced' => $forced, 'hasPassword' => filled($request->user()->password)])->header('Cache-Control', 'no-store, private');
    }

    public function update(Request $request, InternalAccountService $accounts)
    {
        $forced = $request->routeIs('internal.password.*');
        abort_if($forced && ! $request->user()->must_change_password, 403);
        $data = $request->validate([
            'current_password' => ! $forced && filled($request->user()->password) ? ['required', 'current_password:web'] : ['prohibited'],
            'password' => ['required', 'confirmed', Password::min(8), 'max:72'],
        ]);
        $user = $accounts->changePassword($request->user(), $data['password']);
        $request->session()->regenerate(true);
        $request->session()->regenerateToken();
        $request->session()->put('internal_session_version', $user->internal_session_version);

        return to_route($forced ? HomeRoute::for($user) : $this->workspace($request).'.account.password.edit')->with('status', 'Password berhasil diperbarui.');
    }

    private function workspace(Request $request): string
    {
        return $request->user()->hasRole('admin') ? 'admin' : 'verifier';
    }
}
