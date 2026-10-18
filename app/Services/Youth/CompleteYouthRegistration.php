<?php

namespace App\Services\Youth;

use App\Actions\Youth\SyncUserInterestsAction;
use App\Actions\Youth\UpdateDomicileAction;
use App\Actions\Youth\UpdateYouthProfileAction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class CompleteYouthRegistration
{
    public function execute(User $user, array $data): void
    {
        DB::transaction(function () use ($user, $data): void {
            User::whereKey($user->getKey())->lockForUpdate()->firstOrFail();
            app(UpdateYouthProfileAction::class)->execute($user, ['full_name' => $data['name'], 'birth_place' => $data['birth_place'], 'birth_date' => $data['birth_date'], 'gender' => $data['gender']]);
            $user->profileVisibility()->update(['is_profile_public' => false]);
            app(UpdateDomicileAction::class)->execute($user, array_intersect_key($data, array_flip(['administrative_area_id', 'village_id', 'address_line', 'rt', 'rw', 'postal_code'])));
            app(IdentityAnchorService::class)->assign($user, $data['national_id']);
            app(SyncUserInterestsAction::class)->execute($user, $data['interests']);
        });
    }
}
