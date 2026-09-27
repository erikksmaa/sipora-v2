<?php

namespace App\Http\Requests\Admin;

use App\Models\UserIdentityVerification;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ReviewIdentityVerificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') === true
            && $this->user()?->can('verify identities') === true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'decision' => ['required', Rule::in([
                UserIdentityVerification::STATUS_VERIFIED,
                UserIdentityVerification::STATUS_REVISION,
                UserIdentityVerification::STATUS_REJECTED,
            ])],
            'review_notes' => [
                Rule::requiredIf(fn (): bool => in_array($this->input('decision'), [
                    UserIdentityVerification::STATUS_REVISION,
                    UserIdentityVerification::STATUS_REJECTED,
                ], true)),
                'nullable', 'string', 'max:2000',
            ],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'decision.in' => 'Keputusan verifikasi tidak valid.',
            'review_notes.required' => 'Catatan wajib diisi untuk revisi atau penolakan.',
        ];
    }
}
