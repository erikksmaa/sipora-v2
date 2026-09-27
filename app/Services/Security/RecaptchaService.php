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
            ? 'Security verification failed: '.$this->localFailureMessage($failure)
            : 'Security verification failed. Please try again.';

        throw ValidationException::withMessages(['recaptcha_token' => $message]);
    }

    private function localFailureMessage(string $failure): string
    {
        return match ($failure) {
            'configuration_missing' => 'site key, secret key, or token is missing.',
            'connection_failed' => 'the server could not reach Google.',
            'google_http_error' => 'Google returned an HTTP error.',
            'google_rejected_token' => 'Google rejected the token or key pair.',
            'score_missing' => 'Google did not return a v3 score. Check that the key type is score based (v3).',
            'score_too_low' => 'the score is below the configured threshold.',
            'action_mismatch' => 'the returned action does not match this form.',
            'hostname_mismatch' => 'the returned hostname does not match RECAPTCHA_HOSTNAME.',
            default => 'the response could not be validated.',
        };
    }
}
