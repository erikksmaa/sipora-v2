<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class PasswordResetController extends Controller
{
    public function send(AuthRequest $request)
    {
        try {
            $status = Password::sendResetLink($request->safe()->only('email'));
        } catch (TransportExceptionInterface $exception) {
            throw ValidationException::withMessages([
                'email' => 'Tautan reset belum dapat dikirim. Silakan coba lagi nanti atau hubungi pengelola SIPORA.',
            ]);
        }

        if ($status === Password::ResetThrottled) {
            throw ValidationException::withMessages([
                'email' => 'Permintaan terlalu cepat. Tunggu satu menit sebelum meminta tautan baru.',
            ]);
        }

        return back()->with('status', 'Jika akun dengan email tersebut tersedia, tautan reset password telah dikirim. Periksa inbox dan folder spam.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'confirmed', PasswordRule::min(8), 'max:72'],
        ]);
        $data['email'] = mb_strtolower(trim($data['email']));
        $status = Password::reset($data, function (User $user, string $password): void {
            if ($user->isInternalAccount()) {
                app(\App\Services\InternalAccountService::class)->changePassword($user, $password, 'internal_password_reset_completed');
            } else {
                $user->forceFill(['password' => $password, 'remember_token' => Str::random(60)])->save();
                $user->getConnection()->table('sessions')->where('user_id', $user->getKey())->delete();
            }
            event(new PasswordReset($user));
        });
        if ($status !== Password::PasswordReset) {
            throw ValidationException::withMessages(['email' => 'Tautan reset tidak valid atau telah kedaluwarsa. Silakan minta tautan baru.']);
        }

        return to_route('login')->with('status', 'Password berhasil diperbarui. Silakan masuk dengan password baru.');
    }
}
