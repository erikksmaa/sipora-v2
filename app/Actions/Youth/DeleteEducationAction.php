<?php

namespace App\Actions\Youth;

use App\Models\PortfolioEvidence;
use App\Models\User;
use App\Models\UserEducation;
use App\Support\BinaryUuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

final class DeleteEducationAction
{
    public function execute(User $user, string $educationId): void
    {
        $record = $user->educations()->where('id', BinaryUuid::bytesOrFail($educationId, UserEducation::class))->firstOrFail();
        $evidence = PortfolioEvidence::query()->where('user_id', $user->getKey())
            ->where('record_type', 'education')->where('record_id', $record->getKey())->first();

        DB::transaction(function () use ($record, $evidence): void {
            $evidence?->delete();
            $record->delete();
        });

        if ($evidence) {
            Storage::disk('portfolio_evidence')->delete($evidence->file_path);
        }
    }
}
