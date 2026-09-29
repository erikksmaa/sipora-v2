<?php

namespace App\Actions\Manager;

use App\Models\FinancialItem;
use App\Models\FinancialReport;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class SaveFinancialItemAction
{
    public function execute(User $actor, FinancialReport $report, array $data, ?UploadedFile $receipt, ?FinancialItem $item = null): FinancialItem
    {
        $item ??= new FinancialItem;
        $newPath = $receipt?->storeAs('reports/'.$report->uuid(), Str::uuid().'.'.$receipt->extension(), 'financial_receipts');
        $oldPath = $item->receipt_path;

        try {
            $values = ['transaction_type' => $data['transaction_type'], 'transaction_date' => $data['transaction_date'], 'description' => trim($data['description']), 'amount' => $data['amount']];
            if (! $item->exists) {
                $values['financial_report_id'] = $report->getKey();
            }
            if ($newPath !== null) {
                $values['receipt_path'] = $newPath;
            }
            $item->fill($values)->save();
            if ($newPath !== null && $oldPath !== null) {
                Storage::disk('financial_receipts')->delete($oldPath);
            }
            $event = $item->wasRecentlyCreated ? 'financial_item_added' : 'financial_item_updated';
            activity()->causedBy($actor)->performedOn($report)->event($event)->withProperties(['financial_report_id' => $report->uuid(), 'financial_item_id' => $item->uuid()])->log('Item keuangan E-LPJ disimpan');

            return $item;
        } catch (\Throwable $exception) {
            if ($newPath !== null) {
                Storage::disk('financial_receipts')->delete($newPath);
            }
            throw $exception;
        }
    }
}
