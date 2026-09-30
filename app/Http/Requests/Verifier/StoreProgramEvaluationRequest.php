<?php

namespace App\Http\Requests\Verifier;

use App\Models\ProgramEvaluation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreProgramEvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', [ProgramEvaluation::class, $this->route('program')]) === true;
    }

    public function rules(): array
    {
        return [
            'decision' => ['required', Rule::in([ProgramEvaluation::DECISION_APPROVED, ProgramEvaluation::DECISION_REVISION, ProgramEvaluation::DECISION_REJECTED])],
            'evaluation_notes' => ['nullable', 'string', 'max:10000', Rule::requiredIf(fn () => in_array($this->input('decision'), [ProgramEvaluation::DECISION_REVISION, ProgramEvaluation::DECISION_REJECTED], true))],
            'expected_latest_evaluation_id' => ['nullable', 'uuid'],
        ];
    }
}
