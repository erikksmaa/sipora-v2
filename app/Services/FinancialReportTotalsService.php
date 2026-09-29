<?php

namespace App\Services;

use App\Models\FinancialItem;
use App\Models\FinancialReport;

final class FinancialReportTotalsService
{
    public function summarize(FinancialReport $report): array
    {
        $items = $report->relationLoaded('items') ? $report->items : $report->items()->get();
        $income = '0.00';
        $expenses = '0.00';

        foreach ($items as $item) {
            if ($item->transaction_type === FinancialItem::TYPE_INCOME) {
                $income = bcadd($income, $item->amount, 2);
            } else {
                $expenses = bcadd($expenses, $item->amount, 2);
            }
        }

        $budget = $report->program->latestProposal?->requested_budget;
        $difference = $budget === null ? null : bcsub($budget, $expenses, 2);

        return [
            'income_total' => $income,
            'expense_total' => $expenses,
            'realization_total' => $expenses,
            'proposal_budget' => $budget,
            'difference' => $difference,
            'over_budget' => $difference !== null && bccomp($difference, '0.00', 2) < 0,
            'item_count' => $items->count(),
            'evidence_count' => $items->whereNotNull('receipt_path')->count(),
        ];
    }

    public function rupiah(string|int|null $amount): string
    {
        if ($amount === null) {
            return '—';
        }

        $negative = str_starts_with((string) $amount, '-');
        [$whole, $decimal] = array_pad(explode('.', ltrim((string) $amount, '-'), 2), 2, '00');
        $grouped = preg_replace('/\B(?=(\d{3})+(?!\d))/', '.', $whole);

        return ($negative ? '-Rp ' : 'Rp ').$grouped.','.str_pad(substr($decimal, 0, 2), 2, '0');
    }
}
