<?php

namespace App\Http\Requests\Verifier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ReviewCommunityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('verifier') === true;
    }

    public function rules(): array
    {
        return [
            'decision' => ['required', Rule::in(['approved', 'revision', 'rejected'])],
            'review_notes' => [
                Rule::requiredIf(fn () => in_array($this->input('decision'), ['revision', 'rejected'], true)),
                'nullable', 'string', 'max:3000',
            ],
        ];
    }
}
