<?php

namespace App\Support\Media;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

final class UserAvatar
{
    public static function initials(string $name): string
    {
        $parts = preg_split('/\s+/u', trim($name), -1, PREG_SPLIT_NO_EMPTY);
        if (! $parts) return '?';

        return mb_strtoupper(mb_substr($parts[0], 0, 1).(count($parts) > 1 ? mb_substr(end($parts), 0, 1) : ''));
    }

    public static function publicUrl(User $user): ?string
    {
        $profile = $user->profile;
        if (! $user->profileVisibility?->is_profile_public || ! $user->profileVisibility?->show_photo
            || ! $profile?->public_slug || ! $profile->profile_photo_path
            || ! Storage::disk('public')->exists($profile->profile_photo_path)) return null;

        // The public controller accepts a slug, not the profile model's UUID.
        return route('portfolio.photo', ['profile' => $profile->public_slug]);
    }

    public static function accountUrl(User $user): ?string
    {
        // Publicly shared photos do not require completion of the Youth
        // profile stage; use that existing authorized endpoint when available.
        if ($public = self::publicUrl($user)) return $public;

        if ($user->hasRole('youth') && $user->profile?->profile_photo_path
            && Storage::disk('public')->exists($user->profile->profile_photo_path)) {
            return route('youth.profile.photo');
        }

        return null;
    }
}
