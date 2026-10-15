<?php

namespace Database\Seeders;

use App\Services\ChatService;
use Illuminate\Database\Seeder;

class ChatRoomSeeder extends Seeder
{
    public function run(): void
    {
        app(ChatService::class)->globalRoom();
    }
}
