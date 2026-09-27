<?php

namespace App\Actions\Youth;

use App\Models\User;
use App\Models\UserIdentity;
use App\Models\UserProfile;
use App\Models\UserProfileVisibility;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class UpdateYouthProfileAction
{
    public function execute(User $user, array $data, ?UploadedFile $photo = null): UserProfile
    {
        return DB::transaction(function () use ($user, $data, $photo): UserProfile {
            $profile = $user->profile()->firstOrNew();
            $profile->fill($data);
            $profile->public_slug ??= $this->uniqueSlug($data['full_name']);

            if ($photo) {
                $oldPath = $profile->profile_photo_path;
                $profile->profile_photo_path = $photo->storePublicly('profile-photos', 'public');
                if ($oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $profile->save();
            $user->forceFill(['name' => $profile->full_name])->save();
            UserIdentity::firstOrCreate(['user_id' => $user->getKey()]);
            UserProfileVisibility::firstOrCreate(['user_id' => $user->getKey()]);

            return $profile;
        });
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'pemuda';
        do {
            $slug = $base.'-'.Str::lower(Str::random(6));
        } while (UserProfile::withTrashed()->where('public_slug', $slug)->exists());

        return $slug;
    }
}
