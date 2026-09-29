<?php

namespace App\Actions\Manager;

use App\Models\FinancialItem;
use App\Models\FinancialReport;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class CreateFinancialReportRevisionAction
{
    public function execute(User $actor, FinancialReport $source): FinancialReport
    {
        return DB::transaction(function () use ($actor, $source): FinancialReport {
            $source = FinancialReport::query()->with('items')->whereKey($source->getKey())->lockForUpdate()->firstOrFail();
            $latestVersion = (int) $source->program->financialReports()->lockForUpdate()->max('version');
            if ($source->status !== FinancialReport::STATUS_REVISION || (int) $source->version !== $latestVersion) {
                throw ValidationException::withMessages(['report' => ['Revisi E-LPJ tidak lagi dapat dibuat dari versi ini.']]);
            }

            $revision = FinancialReport::create(['program_id' => $source->program_id, 'version' => $latestVersion + 1, 'status' => FinancialReport::STATUS_DRAFT, 'notes' => $source->notes]);
            foreach ($source->items as $sourceItem) {
                $receiptPath = $this->copyReceipt($sourceItem, $revision);
                FinancialItem::create(['financial_report_id' => $revision->getKey(), 'transaction_type' => $sourceItem->transaction_type,
                    'transaction_date' => $sourceItem->transaction_date, 'description' => $sourceItem->description,
                    'amount' => $sourceItem->amount, 'receipt_path' => $receiptPath]);
            }
            activity()->causedBy($actor)->performedOn($revision)->event('financial_report_revision_created')->withProperties(['financial_report_id' => $revision->uuid(), 'source_financial_report_id' => $source->uuid(), 'program_id' => $source->program->uuid(), 'version' => $revision->version])->log('Versi revisi E-LPJ dibuat');

            return $revision;
        });
    }

    private function copyReceipt(FinancialItem $item, FinancialReport $revision): ?string
    {
        if ($item->receipt_path === null || ! Storage::disk('financial_receipts')->exists($item->receipt_path)) {
            return null;
        }

        $path = 'reports/'.$revision->uuid().'/'.Str::uuid().'.'.pathinfo($item->receipt_path, PATHINFO_EXTENSION);
        Storage::disk('financial_receipts')->copy($item->receipt_path, $path);

        return $path;
    }
}
