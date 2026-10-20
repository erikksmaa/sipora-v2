<?php

namespace App\Http\Requests\Auth;

use App\Services\Security\RecaptchaService;
use App\Services\Youth\RegistrationWizard;
use App\Services\Youth\YouthRegistrationRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
                Rule::excludeIf(! config('features.recaptcha')),
                config('recaptcha.enabled') || ! app()->environment(['local', 'testing']) ? 'required' : 'nullable',
                'string',
                'max:8192',
            ],
        ];

        if ($this->routeIs('register.store')) {
            $rules = array_replace($rules, $this->registrationRules());
        } elseif ($this->routeIs('login.store')) {
            $rules['password'] = ['required', 'string', 'max:72'];
            $rules['remember'] = ['sometimes', 'boolean'];
        }

        return $rules;
    }

    public function registrationRules(): array
    {
        return app(YouthRegistrationRules::class)->rules($this->input('administrative_area_id')) + [
            'name' => ['required', 'string', 'max:160'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8), 'max:72'],
        ];
    }

    public function messages(): array
    {
        return ['national_id.required' => 'NIK wajib diisi.', 'national_id.regex' => 'NIK harus terdiri dari 16 digit angka.', 'gender.required' => 'Pilih jenis kelamin.', 'gender.in' => 'Pilih Laki-laki atau Perempuan.', 'interests.max' => 'Pilih maksimal 5 bidang minat.', 'interests.required' => 'Pilih minimal satu bidang minat.'];
    }

    protected function passedValidation(): void
    {
        if ($this->routeIs('register.store')) {
            app(RegistrationWizard::class)->requireComplete($this);
        }
        $action = match ($this->route()->getName()) {
            'register.store' => 'register',
            'login.store' => 'login',
            default => 'forgot_password',
        };
        if (config('features.recaptcha')) {
            app(RecaptchaService::class)->verify((string) $this->validated('recaptcha_token', ''), $action, $this->ip());
        }
    }
}
