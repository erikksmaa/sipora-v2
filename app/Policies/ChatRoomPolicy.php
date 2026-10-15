<?php

namespace App\Policies;

use App\Models\ChatRoom;
use App\Models\Organization;
use App\Models\OrganizationMembership;
use App\Models\User;

final class ChatRoomPolicy
{
    public function view(User $user, ChatRoom $room): bool
    {
        if (! $user->hasVerifiedEmail() || ! $user->hasAnyRole(['youth', 'admin', 'verifier']) || ($user->isInternalAccount() && ! $user->internal_active)) {
            return false;
        }
        if ($room->type === 'global' || $user->hasRole('admin')) {
            return true;
        }

        return $room->community()->exists() && $this->membership($user, $room)->exists();
    }

    public function send(User $user, ChatRoom $room): bool
    {
        if (! $this->view($user, $room) || ! $room->is_active) {
            return false;
        }
        if ($room->type === 'global') {
            return true;
        }

        return $room->community()->where('operational_status', Organization::OPERATIONAL_ACTIVE)->exists() && $this->membership($user, $room)->exists();
    }

    public function moderate(User $user, ChatRoom $room): bool
    {
        return $this->view($user, $room) && ($user->hasRole('admin') || ($room->type === 'community' && $this->membership($user, $room)->whereIn('access_role', ['leader', 'manager'])->exists()));
    }

    private function membership(User $user, ChatRoom $room)
    {
        return OrganizationMembership::where('organization_id', $room->community_id)->where('user_id', $user->getKey())->where('membership_status', 'active');
    }
}
