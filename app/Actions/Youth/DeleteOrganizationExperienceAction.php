<?php

namespace App\Actions\Youth;

use App\Models\OrganizationExperience;
use App\Models\PortfolioEvidence;
use App\Models\User;
use App\Support\BinaryUuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

final class DeleteOrganizationExperienceAction
{
    public function execute(User $user, string $experienceId): void
    {
        $record = $user->organizationExperiences()->where('id', BinaryUuid::bytesOrFail($experienceId, OrganizationExperience::class))->firstOrFail();
        $evidence = PortfolioEvidence::query()->where('user_id', $user->getKey())
            ->where('record_type', 'organization')->where('record_id', $record->getKey())->first();

        DB::transaction(function () use ($record, $evidence): void {
            $evidence?->delete();
            $record->delete();
        });

        if ($evidence) {
            Storage::disk('portfolio_evidence')->delete($evidence->file_path);
        }
    }
}
