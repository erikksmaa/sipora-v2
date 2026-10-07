<?php

namespace Tests\Feature;

use Tests\TestCase;

// Rendering only: no database refresh, seed, or account mutations.
class AlertRenderingTest extends TestCase
{
    public function test_auth_pages_include_alert_bridge(): void
    {
        foreach (['/login', '/register', '/forgot-password', '/reset-password/test-token'] as $url) {
            $this->get($url)->assertOk()->assertSee('id="sipora-alert-data"', false);
        }
    }

    public function test_flash_message_is_json_encoded_without_executable_markup(): void
    {
        $message = '</script><img src=x onerror=alert(1)>';
        $response = $this->withSession(['status' => $message])->get('/login')->assertOk();
        preg_match('/<script type="application\/json" id="sipora-alert-data">(.*?)<\/script>/s', $response->getContent(), $matches);
        $this->assertStringNotContainsString('<img', $matches[1]);
        $this->assertSame([['icon' => 'success', 'text' => $message]], json_decode($matches[1], true));
    }

    public function test_validation_failure_takes_priority_over_success(): void
    {
        $errors = ['default' => ['format' => ':message', 'messages' => ['email' => ['Email tidak valid.']]]];
        $response = $this->withSession(['errors' => $errors, 'status' => 'Saved'])->get('/login')->assertOk();
        preg_match('/<script type="application\/json" id="sipora-alert-data">(.*?)<\/script>/s', $response->getContent(), $matches);
        $this->assertSame([['icon' => 'error', 'text' => 'Email tidak valid.']], json_decode($matches[1], true));
    }
}
