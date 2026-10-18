<?php

namespace App\Services\Youth;

use App\Models\User;

final class GoogleOnboardingService
{
    public function pending(User $user): bool
    {
        if (! $user->hasRole('youth') || $user->isInternalAccount()
            || ! $user->socialAccounts()->where('provider', 'google')->exists()) {
            return false;
        }
        // Preserve the existing completion contract for legacy Google/local accounts.
        $profile = $user->profile()->first();

        return ! (filled($profile?->full_name) && $profile?->birth_date !== null
            && $user->primaryDomicile()->exists());
    }
}
