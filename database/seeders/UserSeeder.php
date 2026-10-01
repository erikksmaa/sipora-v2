<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public const PASSWORD = 'password';

    public function run(): void
    {
        foreach ([
            ['name' => 'Admin SIPORA', 'email' => 'admin@sipora.test', 'role' => 'admin'],
            ['name' => 'Verifikator Dindikpora', 'email' => 'verifier@sipora.test', 'role' => 'verifier'],
            ['name' => 'Erik Kusuma Rais', 'email' => 'youth1@sipora.test', 'role' => 'youth'],
            ['name' => 'Nabila Putri Salsabila', 'email' => 'youth2@sipora.test', 'role' => 'youth'],
            ['name' => 'Bagas Prasetyo Utomo', 'email' => 'youth3@sipora.test', 'role' => 'youth'],
            ['name' => 'Dinda Ayu Lestari', 'email' => 'youth4@sipora.test', 'role' => 'youth'],
            ['name' => 'Rizky Aditya', 'email' => 'youth5@sipora.test', 'role' => 'youth'],
            ['name' => 'Alya Rahmawati', 'email' => 'youth6@sipora.test', 'role' => 'youth'],
            ['name' => 'Fajar Nugroho', 'email' => 'youth7@sipora.test', 'role' => 'youth'],
            ['name' => 'Siti Maharani', 'email' => 'youth8@sipora.test', 'role' => 'youth'],
            ['name' => 'Dimas Saputra', 'email' => 'youth9@sipora.test', 'role' => 'youth'],
            ['name' => 'Nadia Kurnia', 'email' => 'youth10@sipora.test', 'role' => 'youth'],
        ] as $record) {
            $user = User::query()->updateOrCreate(
                ['email' => $record['email']],
                ['name' => $record['name'], 'password' => Hash::make(self::PASSWORD), 'email_verified_at' => now()]
            );
            $user->syncRoles([$record['role']]);
        }
    }
}
