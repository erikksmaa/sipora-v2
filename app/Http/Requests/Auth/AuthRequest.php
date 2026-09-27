<?php

namespace App\Http\Requests\Auth;

use App\Services\Security\RecaptchaService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class AuthRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('email'))) {
            $this->merge(['email' => mb_strtolower(trim($this->input('email')))]);
        }
    }

    public function rules(): array
    {
        $rules = [
            'email' => ['required', 'email', 'max:255'],
            'recaptcha_token' => [
                config('recaptcha.enabled') || ! app()->environment(['local', 'testing']) ? 'required' : 'nullable',
                'string',
                'max:8192',
            ],
        ];

        if ($this->routeIs('register.store')) {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['email'][] = 'unique:users,email';
            $rules['password'] = ['required', 'confirmed', Password::min(12), 'max:72'];
        } elseif ($this->routeIs('login.store')) {
            $rules['password'] = ['required', 'string', 'max:72'];
            $rules['remember'] = ['sometimes', 'boolean'];
        }

        return $rules;
    }

    protected function passedValidation(): void
    {
        $action = match ($this->route()->getName()) {
            'register.store' => 'register',
            'login.store' => 'login',
            default => 'forgot_password',
        };
        app(RecaptchaService::class)->verify((string) $this->validated('recaptcha_token', ''), $action, $this->ip());
    }
}
