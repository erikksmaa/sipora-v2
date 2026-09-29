<?php

namespace App\Http\Requests\Manager;

use App\Models\FinancialReport;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class SaveFinancialItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        $report = $this->route('report');

        return $report instanceof FinancialReport && $this->user()?->can('update', $report) === true;
    }

    public function rules(): array
    {
        return [
            'transaction_type' => ['required', Rule::in(['income', 'expense'])],
            'transaction_date' => ['required', 'date'],
            'description' => ['required', 'string', 'max:10000'],
            'amount' => ['required', 'decimal:0,2', 'gt:0', 'max:9999999999999999.99'],
            'receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:8192'],
        ];
    }
}
