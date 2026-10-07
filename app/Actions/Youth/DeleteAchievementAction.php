<?php

namespace App\Actions\Youth;

use App\Models\User;
use App\Models\UserAchievement;
use App\Support\BinaryUuid;
use Illuminate\Support\Facades\Storage;

final class DeleteAchievementAction
{
    public function execute(User $user, string $achievementId): void
    {
        $record = $user->achievements()->where('id', BinaryUuid::bytesOrFail($achievementId, UserAchievement::class))->firstOrFail();
        $path = $record->evidence_path;
        $record->delete();

        if ($path) {
            Storage::disk('portfolio_evidence')->delete($path);
        }
    }
}
