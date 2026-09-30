<?php

namespace App\Actions\Admin;

use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Validation\ValidationException;

final class ChangeOpportunityPublicationAction
{
    public function execute(User $admin, Opportunity $opportunity, string $status): Opportunity
    {
        if (! in_array($status, [Opportunity::STATUS_PUBLISHED, Opportunity::STATUS_ARCHIVED], true)) {
            throw ValidationException::withMessages(['publication_status' => 'Transisi publikasi tidak valid.']);
        }
        if ($opportunity->publication_status === $status) {
            throw ValidationException::withMessages(['publication_status' => 'Status Opportunity tidak berubah.']);
        }

        $opportunity->forceFill([
            'publication_status' => $status,
            'published_at' => $status === Opportunity::STATUS_PUBLISHED ? now() : $opportunity->published_at,
        ])->save();

        activity()->causedBy($admin)->performedOn($opportunity)->event('opportunity_'.$status)
            ->withProperties(['opportunity_id' => $opportunity->uuid(), 'publication_status' => $status])
            ->log($status === Opportunity::STATUS_PUBLISHED ? 'Opportunity dipublikasikan' : 'Opportunity diarsipkan');

        return $opportunity;
    }
}
