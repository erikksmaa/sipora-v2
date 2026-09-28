<?php

namespace App\Http\Requests\Verifier;

use App\Models\Activity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ReviewActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('verifier') === true;
    }

    public function rules(): array
    {
        return [
            'decision' => ['required', Rule::in([Activity::REVIEW_APPROVED, Activity::REVIEW_REVISION, Activity::REVIEW_REJECTED])],
            'notes' => ['nullable', 'required_if:decision,'.Activity::REVIEW_REVISION.','.Activity::REVIEW_REJECTED, 'string', 'max:3000'],
        ];
    }
}
