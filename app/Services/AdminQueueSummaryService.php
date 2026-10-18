<?php

namespace App\Services;

use App\Models\ChatMessageReport;
use App\Models\ForumReport;
use App\Models\SocialReport;
use App\Models\UserIdentityVerification;

final class AdminQueueSummaryService
{
    public function navigation(): array
    {
        return [
            route('admin.identity-verifications.index') => UserIdentityVerification::where('status', UserIdentityVerification::STATUS_PENDING)->count(),
            route('admin.social.index') => SocialReport::where('status', 'pending')->count(),
            route('admin.forum.index') => ForumReport::where('status', 'pending')->count(),
            route('admin.chat.index') => ChatMessageReport::where('status', 'pending')->count(),
        ];
    }
}
