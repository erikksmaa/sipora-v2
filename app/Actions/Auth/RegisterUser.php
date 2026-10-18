<?php

namespace App\Actions\Auth;

use App\Enums\SystemRole;
use App\Models\User;
use App\Services\Youth\CompleteYouthRegistration;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RegisterUser
{
    public function execute(array $data): User
    {
        try {
            return DB::transaction(function () use ($data): User {
                $user = User::create([
                    'name' => $data['name'], 'email' => $data['email'], 'password' => $data['password'],
                ]);
                $user->assignRole(SystemRole::Youth);

                app(CompleteYouthRegistration::class)->execute($user, $data);

                return $user;
            });
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages(['email' => 'Data pendaftaran sudah digunakan. Silakan masuk atau gunakan pemulihan akun.']);
        }
    }
}
