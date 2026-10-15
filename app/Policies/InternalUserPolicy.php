<?php

namespace App\Policies;

use App\Models\User;

final class InternalUserPolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->hasRole('admin');
    }

    public function view(User $actor, User $target): bool
    {
        return $this->viewAny($actor) && $target->isInternalAccount();
    }

    public function manage(User $actor, User $target): bool
    {
        return $this->view($actor, $target) && ! $actor->is($target);
    }
}
