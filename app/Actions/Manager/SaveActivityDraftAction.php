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
        $oldProgramId = $activity->program_id;
        $values = Arr::except($data, ['poster']);
        $values['category_id'] = BinaryUuid::bytes($values['category_id']);
        $values['program_id'] = filled($values['program_id'] ?? null) ? BinaryUuid::bytes($values['program_id']) : null;
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

        if ($oldProgramId !== $activity->program_id) {
            $event = $activity->program_id === null ? 'activity_unlinked_from_program' : 'activity_linked_to_program';
            activity()->causedBy($actor)->performedOn($activity)->event($event)
                ->withProperties([
                    'activity_id' => $activity->uuid(),
                    'previous_program_id' => $oldProgramId === null ? null : BinaryUuid::text($oldProgramId),
                    'program_id' => $activity->program?->uuid(),
                ])->log($activity->program_id === null ? 'Activity dilepas dari Program' : 'Activity dihubungkan ke Program');
        }

        if ($poster && $oldPoster) {
            Storage::disk('activity_media')->delete($oldPoster);
        }

        return $activity->fresh(['category', 'organization', 'program']);
    }
}
