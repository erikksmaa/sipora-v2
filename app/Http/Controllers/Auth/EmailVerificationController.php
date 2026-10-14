<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Support\Auth\HomeRoute;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class EmailVerificationController extends Controller
{
    public function notice(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? to_route(HomeRoute::for($request->user()))
            : view('auth.verify-email');
    }

    public function verify(VerifyEmailRequest $request)
    {
        $request->fulfill();

        return to_route(HomeRoute::for($request->user()))->with('status', 'Email berhasil diverifikasi. Silakan lanjutkan perjalanan Anda di SIPORA.');
    }

    public function send(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return to_route(HomeRoute::for($request->user()));
        }

        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (TransportExceptionInterface $exception) {
            throw ValidationException::withMessages([
                'email' => 'Email verifikasi belum dapat dikirim. Silakan coba lagi nanti atau hubungi pengelola SIPORA.',
            ]);
        }

        return back()->with('status', 'Tautan verifikasi baru telah dikirim. Periksa inbox dan folder spam.');
    }
}
