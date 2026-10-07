<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RegisterUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function store(AuthRequest $request, RegisterUser $register): RedirectResponse
    {
        $user = $register->execute($request->validated());
        event(new Registered($user));
        Auth::login($user);
        $request->session()->regenerate();

        return to_route('verification.notice')->with('status', 'Akun berhasil dibuat. Silakan verifikasi alamat email Anda.');
    }
}
