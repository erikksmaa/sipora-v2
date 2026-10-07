<?php

namespace App\Http\Requests\Youth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

final class UpdateAccountPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('youth') === true;
    }

    public function rules(): array
    {
        return [
            'current_password' => filled($this->user()?->password)
                ? ['required', 'current_password:web']
                : ['prohibited'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ];
    }
}
