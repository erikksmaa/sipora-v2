<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RegisterUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class RegistrationController extends Controller
{
    public function store(AuthRequest $request, RegisterUser $register): RedirectResponse
    {
        $user = $register->execute($request->validated());
        $mailFailed = false;
        try {
            event(new Registered($user));
        } catch (TransportExceptionInterface $exception) {
            $mailFailed = true;
        }
        Auth::login($user);
        $request->session()->regenerate();

        if ($mailFailed) {
            return to_route('verification.notice')->with('error', 'Akun berhasil dibuat, tetapi email verifikasi belum dapat dikirim. Gunakan tombol kirim ulang atau coba lagi nanti.');
        }

        return to_route('verification.notice')->with('status', 'Akun berhasil dibuat. Silakan verifikasi alamat email Anda.');
    }
}
