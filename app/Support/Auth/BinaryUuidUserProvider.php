<?php

namespace App\Support\Auth;

use App\Support\BinaryUuid;
use Illuminate\Auth\EloquentUserProvider;
use InvalidArgumentException;

class BinaryUuidUserProvider extends EloquentUserProvider
{
    public function retrieveById($identifier)
    {
        try {
            return parent::retrieveById(BinaryUuid::bytes($identifier));
        } catch (InvalidArgumentException) {
            return null;
        }
    }

    public function retrieveByToken($identifier, #[\SensitiveParameter] $token)
    {
        try {
            return parent::retrieveByToken(BinaryUuid::bytes($identifier), $token);
        } catch (InvalidArgumentException) {
            return null;
        }
    }
}
