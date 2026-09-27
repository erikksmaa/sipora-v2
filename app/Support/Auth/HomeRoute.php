<?php

namespace App\Support\Auth;

use App\Enums\SystemRole;
use App\Models\User;

final class HomeRoute
{
    public static function for(User $user): string
    {
        return match (true) {
            $user->hasRole(SystemRole::Admin) => 'admin.dashboard',
            $user->hasRole(SystemRole::Verifier) => 'verifier.dashboard',
            default => 'youth.home',
        };
    }
}
