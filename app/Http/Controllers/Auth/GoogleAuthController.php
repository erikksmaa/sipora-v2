<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\AuthenticateGoogleUser;
use App\Http\Controllers\Controller;
use App\Support\Auth\HomeRoute;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        abort_unless($this->configured(), 503, 'Google sign-in is not configured.');

        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request, AuthenticateGoogleUser $authenticate)
    {
        if (! $this->configured() || $request->has('error')) {
            return to_route('login')->withErrors(['email' => 'Google sign-in was unavailable or cancelled.']);
        }

        try {
            // Stateful Socialite verifies the OAuth state before exchanging code.
            $user = $authenticate->execute(Socialite::driver('google')->user());
        } catch (InvalidStateException|GuzzleException|UniqueConstraintViolationException|ValidationException $exception) {
            // Do not log the callback URL, authorization code, tokens, or account data.
            Log::warning('Google sign-in callback failed.', [
                'failure_type' => class_basename($exception),
            ]);

            return to_route('login')->withErrors([
                'email' => $exception instanceof ValidationException
                    ? $exception->errors()['email'][0]
                    : ($exception instanceof InvalidStateException
                        ? 'Sesi masuk Google tidak cocok atau sudah kedaluwarsa. Buka kembali halaman masuk dan coba sekali lagi.'
                        : 'Google sign-in failed. Please try again or use email sign-in.'),
            ]);
        }
        Auth::login($user);
        $request->session()->regenerate();

        return to_route(HomeRoute::for($user))->with('status', 'Selamat datang! Anda berhasil masuk melalui Google.');
    }

    private function configured(): bool
    {
        return (bool) (config('services.google.client_id')
            && config('services.google.client_secret') && config('services.google.redirect'));
    }
}
