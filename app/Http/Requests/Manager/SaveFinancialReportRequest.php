<?php

namespace App\Http\Requests\Manager;

use App\Models\FinancialReport;
use Illuminate\Foundation\Http\FormRequest;

final class SaveFinancialReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        $report = $this->route('report');

        return $report instanceof FinancialReport
            ? $this->user()?->can('update', $report) === true
            : $this->user()?->can('create', [FinancialReport::class, $this->route('program')]) === true;
    }

    public function rules(): array
    {
        return ['notes' => ['nullable', 'string', 'max:10000']];
    }
}
