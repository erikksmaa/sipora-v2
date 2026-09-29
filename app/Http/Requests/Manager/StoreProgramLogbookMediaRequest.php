<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

final class StoreProgramLogbookMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('logbook') && $this->user()?->can('update', $this->route('logbook'));
    }

    public function rules(): array
    {
        return ['file' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:8192'], 'caption' => ['nullable', 'string', 'max:1000']];
    }
}
