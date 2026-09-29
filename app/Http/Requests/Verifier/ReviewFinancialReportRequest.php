<?php

namespace App\Http\Requests\Verifier;

use App\Models\FinancialReport;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ReviewFinancialReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('review', $this->route('report')) === true;
    }

    public function rules(): array
    {
        return [
            'decision' => ['required', Rule::in([FinancialReport::STATUS_APPROVED, FinancialReport::STATUS_REVISION, FinancialReport::STATUS_REJECTED])],
            'notes' => ['nullable', 'string', 'max:10000', Rule::requiredIf(fn () => in_array($this->input('decision'), [FinancialReport::STATUS_REVISION, FinancialReport::STATUS_REJECTED], true))],
        ];
    }
}
