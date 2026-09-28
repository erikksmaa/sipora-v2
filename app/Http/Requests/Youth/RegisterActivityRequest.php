<?php

namespace App\Http\Requests\Youth;

use Illuminate\Foundation\Http\FormRequest;

final class RegisterActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('youth') === true;
    }

    public function rules(): array
    {
        return ['registration_notes' => ['nullable', 'string', 'max:2000']];
    }
}
