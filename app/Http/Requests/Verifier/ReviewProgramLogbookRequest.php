<?php

namespace App\Http\Requests\Verifier;

use App\Models\ProgramLogbook;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ReviewProgramLogbookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('logbook') && $this->user()?->can('review', $this->route('logbook'));
    }

    public function rules(): array
    {
        return ['decision' => ['required', Rule::in([ProgramLogbook::STATUS_APPROVED, ProgramLogbook::STATUS_REVISION])], 'notes' => ['nullable', 'required_if:decision,'.ProgramLogbook::STATUS_REVISION, 'string', 'max:5000']];
    }
}
