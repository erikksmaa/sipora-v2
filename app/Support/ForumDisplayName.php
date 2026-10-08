<?php

namespace App\Support;

use App\Models\User;

final class ForumDisplayName
{
    public static function for(User $user): string
    {
        if ($user->profileVisibility?->is_profile_public && filled($user->profile?->full_name)) {
            return $user->profile->full_name;
        }

        return 'Pemuda SIPORA #'.strtoupper(substr(hash_hmac('sha256', $user->getKey(), config('app.key')), 0, 6));
    }
}
