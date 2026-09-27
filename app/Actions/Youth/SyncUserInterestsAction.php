<?php

namespace App\Actions\Youth;

use App\Models\User;
use App\Models\UserInterest;
use App\Support\BinaryUuid;
use Illuminate\Support\Facades\DB;

final class SyncUserInterestsAction
{
    public function execute(User $user, array $interestIds): void
    {
        DB::transaction(function () use ($user, $interestIds): void {
            $binaryIds = array_map(BinaryUuid::bytes(...), array_unique($interestIds));
            UserInterest::where('user_id', $user->getKey())->whereNotIn('interest_id', $binaryIds)->delete();
            foreach ($binaryIds as $interestId) {
                UserInterest::withTrashed()->updateOrCreate(
                    ['user_id' => $user->getKey(), 'interest_id' => $interestId],
                    ['deleted_at' => null]
                );
            }
        });
    }
}
