<?php

namespace App\Http\Requests\Youth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

final class UpsertAchievementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:180'],
            'issuer_name' => ['nullable', 'string', 'max:180'],
            'achievement_date' => ['nullable', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
            'evidence' => ['nullable', File::types(['pdf', 'jpg', 'jpeg', 'png'])->max(5 * 1024)],
        ];
    }
}
