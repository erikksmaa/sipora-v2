<?php

namespace Tests\Feature;

use App\Services\Security\RecaptchaService;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RecaptchaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
        config(['recaptcha.enabled' => true, 'recaptcha.secret_key' => 'test-secret', 'recaptcha.hostname' => 'localhost', 'recaptcha.min_score' => 0.5]);
    }

    public function test_valid_captcha_uses_configured_action_and_score(): void
    {
        config(['recaptcha.actions.login' => 'custom_login']);
        Http::fake(['www.google.com/recaptcha/api/siteverify' => Http::response([
            'success' => true, 'score' => 0.5, 'action' => 'custom_login', 'hostname' => 'localhost',
        ])]);
        app(RecaptchaService::class)->verify('fake-token', 'login', '127.0.0.1');
        Http::assertSent(fn ($request) => $request['response'] === 'fake-token' && $request['secret'] === 'test-secret');
    }

    public static function invalidResponses(): array
    {
        return [
            'unsuccessful' => [['success' => false]],
            'low score' => [['score' => 0.49]],
            'wrong action' => [['action' => 'register']],
            'wrong host' => [['hostname' => 'attacker.test']],
            'missing score' => [['score' => null]],
        ];
    }

    #[DataProvider('invalidResponses')]
    public function test_invalid_provider_response_is_rejected(array $override): void
    {
        Http::fake(['*' => Http::response(array_merge([
            'success' => true, 'score' => 0.9, 'action' => 'login', 'hostname' => 'localhost',
        ], $override))]);
        $this->expectException(ValidationException::class);
        app(RecaptchaService::class)->verify('fake-token', 'login');
    }

    public function test_network_failure_fails_closed(): void
    {
        Http::fake(['*' => Http::failedConnection()]);
        $this->expectException(ValidationException::class);
        app(RecaptchaService::class)->verify('fake-token', 'login');
    }

    public function test_missing_secret_fails_closed_without_network_request(): void
    {
        config(['recaptcha.secret_key' => null]);
        try {
            app(RecaptchaService::class)->verify('fake-token', 'login');
            $this->fail('Missing configuration must be rejected.');
        } catch (ValidationException) {
            Http::assertNothingSent();
        }
    }

    public function test_local_environment_can_explicitly_disable_recaptcha(): void
    {
        config(['recaptcha.enabled' => false, 'recaptcha.secret_key' => null]);

        app(RecaptchaService::class)->verify('', 'login');

        Http::assertNothingSent();
    }
}
