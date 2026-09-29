<?php

namespace App\Actions\Manager;

use App\Models\Organization;
use App\Models\Program;
use App\Models\User;
use App\Support\BinaryUuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class SaveProgramAction
{
    public function execute(User $actor, Organization $organization, array $data, ?Program $program = null): Program
    {
        return DB::transaction(function () use ($actor, $organization, $data, $program): Program {
            $creating = $program === null;
            $program = $program === null
                ? new Program
                : Program::query()->whereKey($program->getKey())->lockForUpdate()->firstOrFail();

            $program->fill([
                'category_id' => BinaryUuid::bytes($data['category_id']),
                'title' => trim($data['title']),
                'description' => $data['description'] ?? null,
                'objectives' => $data['objectives'] ?? null,
                'start_date' => $data['start_date'] ?? null,
                'end_date' => $data['end_date'] ?? null,
            ]);

            if ($creating) {
                $program->forceFill([
                    'organization_id' => $organization->getKey(),
                    'created_by_user_id' => $actor->getKey(),
                    'slug' => Str::slug($data['title']).'-'.Str::lower(Str::random(8)),
                    'execution_status' => Program::STATUS_PLANNED,
                ]);
            }

            $program->save();
            activity()->causedBy($actor)->performedOn($program)
                ->event($creating ? 'program_created' : 'program_updated')
                ->withProperties(['program_id' => $program->uuid(), 'organization_id' => $organization->uuid()])
                ->log($creating ? 'Program dibuat' : 'Program diperbarui');

            return $program->fresh(['category', 'organization']);
        });
    }
}
