<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\OrganizationMembership;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrganizationMembershipSeeder extends Seeder
{
    public function run(): void
    {
        $youth1 = $this->user('youth1@sipora.test');
        $records = [
            ['organization' => 'komunitas-programmer-pemalang', 'email' => 'youth1@sipora.test', 'role' => 'leader', 'status' => 'active'],
            ['organization' => 'komunitas-programmer-pemalang', 'email' => 'youth2@sipora.test', 'role' => 'manager', 'status' => 'active'],
            ['organization' => 'komunitas-programmer-pemalang', 'email' => 'youth3@sipora.test', 'role' => 'member', 'status' => 'active'],
            ['organization' => 'komunitas-programmer-pemalang', 'email' => 'youth4@sipora.test', 'role' => 'member', 'status' => 'pending'],
            ['organization' => 'komunitas-programmer-pemalang', 'email' => 'youth5@sipora.test', 'role' => 'member', 'status' => 'rejected'],
            ['organization' => 'pemuda-olahraga-pemalang', 'email' => 'youth3@sipora.test', 'role' => 'leader', 'status' => 'active'],
            ['organization' => 'pemuda-olahraga-pemalang', 'email' => 'youth2@sipora.test', 'role' => 'member', 'status' => 'active'],
            ['organization' => 'pemuda-olahraga-pemalang', 'email' => 'youth5@sipora.test', 'role' => 'member', 'status' => 'left'],
        ];

        foreach ($records as $record) {
            $organization = Organization::query()->where('slug', $record['organization'])->firstOrFail();
            $user = $this->user($record['email']);
            $active = $record['status'] === OrganizationMembership::STATUS_ACTIVE;
            OrganizationMembership::withTrashed()->updateOrCreate(
                ['organization_id' => $organization->getKey(), 'user_id' => $user->getKey()],
                ['access_role' => $record['role'], 'position_title' => $record['role'] === 'leader' ? 'Ketua' : null,
                    'membership_status' => $record['status'], 'requested_at' => now()->subMonths(2),
                    'approved_at' => $active ? now()->subMonths(2)->addDay() : null, 'approved_by' => $active ? $youth1->getKey() : null,
                    'ended_at' => $record['status'] === 'left' ? now()->subWeek() : null, 'deleted_at' => null]
            );
        }
    }

    private function user(string $email): User
    {
        return User::query()->where('email', $email)->firstOrFail();
    }
}
