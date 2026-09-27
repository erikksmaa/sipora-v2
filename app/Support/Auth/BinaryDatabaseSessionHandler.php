<?php

namespace App\Support\Auth;

use App\Support\BinaryUuid;
use Illuminate\Session\DatabaseSessionHandler;

class BinaryDatabaseSessionHandler extends DatabaseSessionHandler
{
    protected function userId()
    {
        $id = parent::userId();

        return $id === null ? null : BinaryUuid::bytes($id);
    }
}
