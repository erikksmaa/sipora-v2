<?php

namespace App\Services\Security;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RecaptchaService
{
    public function verify(string $token, string $action, ?string $ip = null): void
    {
        if (! config('recaptcha.enabled') && app()->environment(['local', 'testing'])) {
            return;
        }

        $failure = 'configuration_missing';
        if (config('recaptcha.secret_key') && $token !== '') {
            try {
                $response = Http::asForm()
                    ->withOptions(['proxy' => config('recaptcha.http_proxy') ?: ''])
                    ->timeout(8)
                    ->post('https://www.google.com/recaptcha/api/siteverify', [
                        'secret' => config('recaptcha.secret_key'),
                        'response' => $token,
                        'remoteip' => $ip,
                    ]);
                $data = $response->json();
                $expectedAction = config('recaptcha.actions.'.$action);
                $expectedHostnames = array_values(array_unique(array_filter([
                    config('recaptcha.hostname'),
                    ...config('recaptcha.hostnames', []),
                ])));

                $failure = match (true) {
                    ! $response->successful() => 'google_http_error',
                    ($data['success'] ?? false) !== true => 'google_rejected_token',
                    ! is_numeric($data['score'] ?? null) => 'score_missing',
                    (float) $data['score'] < (float) config('recaptcha.min_score') => 'score_too_low',
                    ($data['action'] ?? null) !== $expectedAction => 'action_mismatch',
                    $expectedHostnames !== [] && ! in_array($data['hostname'] ?? null, $expectedHostnames, true) => 'hostname_mismatch',
                    default => null,
                };

                if ($failure === null) {
                    return;
                }

                Log::warning('reCAPTCHA verification rejected.', [
                    'reason' => $failure,
                    'http_status' => $response->status(),
                    'error_codes' => $data['error-codes'] ?? [],
                    'score' => $data['score'] ?? null,
                    'expected_action' => $expectedAction,
                    'received_action' => $data['action'] ?? null,
                    'expected_hostnames' => $expectedHostnames,
                    'received_hostname' => $data['hostname'] ?? null,
                ]);
            } catch (ConnectionException $exception) {
                $failure = 'connection_failed';
                Log::warning('reCAPTCHA verification connection failed.', [
                    'reason' => $failure,
                    'exception' => $exception::class,
                ]);
            }
        }

        $message = app()->environment('local')
            ? 'Verifikasi keamanan gagal: '.$this->localFailureMessage($failure)
            : 'Verifikasi keamanan gagal. Silakan coba lagi.';

        throw ValidationException::withMessages(['recaptcha_token' => $message]);
    }

    private function localFailureMessage(string $failure): string
    {
        return match ($failure) {
            'configuration_missing' => 'kunci situs, kunci rahasia, atau token belum tersedia.',
            'connection_failed' => 'server tidak dapat terhubung ke Google.',
            'google_http_error' => 'Google mengembalikan kesalahan HTTP.',
            'google_rejected_token' => 'Google menolak token atau pasangan kunci.',
            'score_missing' => 'Google tidak mengembalikan hasil verifikasi v3. Pastikan kunci menggunakan jenis v3 berbasis skor.',
            'score_too_low' => 'hasil verifikasi berada di bawah ambang yang ditetapkan.',
            'action_mismatch' => 'tindakan yang dikembalikan tidak sesuai dengan formulir ini.',
            'hostname_mismatch' => 'nama host yang dikembalikan tidak sesuai dengan konfigurasi RECAPTCHA_HOSTNAME.',
            default => 'respons tidak dapat divalidasi.',
        };
    }
}
