<?php

namespace App\Actions\Youth;

use App\Models\User;
use App\Models\UserAddress;
use App\Support\BinaryUuid;

final class UpdateDomicileAction
{
    public function execute(User $user, array $data): UserAddress
    {
        return $user->primaryDomicile()->updateOrCreate(
            ['address_type' => 'domicile', 'is_primary' => true],
            [...$data, 'administrative_area_id' => BinaryUuid::bytes($data['administrative_area_id'])]
        );
    }
}
