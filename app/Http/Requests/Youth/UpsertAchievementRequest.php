<?php

namespace App\Http\Requests\Youth;

use Illuminate\Foundation\Http\FormRequest;

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
            'evidence_path' => ['nullable', 'string', 'max:500'],
        ];
    }
}
