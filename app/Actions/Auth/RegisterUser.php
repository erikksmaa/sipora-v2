<?php

namespace App\Actions\Auth;

use App\Enums\SystemRole;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RegisterUser
{
    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data): User {
            $user = User::create([
                'name' => $data['name'], 'email' => $data['email'], 'password' => $data['password'],
            ]);
            $user->assignRole(SystemRole::Youth);

            return $user;
        });
    }
}
