<?php

namespace App\Actions\Manager;

use App\Models\Activity;
use App\Models\Organization;
use App\Models\User;
use App\Support\BinaryUuid;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class SaveActivityDraftAction
{
    public function execute(User $actor, Organization $organization, array $data, ?UploadedFile $poster = null, ?Activity $activity = null): Activity
    {
        $activity ??= new Activity;
        $oldPoster = $activity->poster_path;
        $values = Arr::except($data, ['poster']);
        $values['category_id'] = BinaryUuid::bytes($values['category_id']);
        $values['administrative_area_id'] = filled($values['administrative_area_id'] ?? null)
            ? BinaryUuid::bytes($values['administrative_area_id']) : null;
        foreach (['requires_identity_verification', 'members_only', 'certificate_enabled'] as $boolean) {
            $values[$boolean] = (bool) ($values[$boolean] ?? false);
        }

        if (! $activity->exists) {
            $values += [
                'organization_id' => $organization->getKey(),
                'created_by_user_id' => $actor->getKey(),
                'slug' => Str::slug($values['title']).'-'.Str::lower(Str::random(8)),
                'review_status' => Activity::REVIEW_DRAFT,
                'publication_status' => Activity::PUBLICATION_UNPUBLISHED,
                'execution_status' => Activity::EXECUTION_SCHEDULED,
            ];
        }

        if ($poster) {
            $values['poster_path'] = $poster->store('posters', 'activity_media');
        }
        $activity->fill($values)->save();

        if ($poster && $oldPoster) {
            Storage::disk('activity_media')->delete($oldPoster);
        }

        return $activity->fresh(['category', 'organization']);
    }
}
