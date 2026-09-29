<?php

namespace App\Actions\Manager;

use App\Models\FinancialItem;
use App\Models\User;

final class DeleteFinancialItemAction
{
    public function execute(User $actor, FinancialItem $item): void
    {
        $report = $item->report;
        $item->delete();
        activity()->causedBy($actor)->performedOn($report)->event('financial_item_removed')->withProperties(['financial_report_id' => $report->uuid(), 'financial_item_id' => $item->uuid()])->log('Item keuangan E-LPJ diarsipkan');
    }
}
