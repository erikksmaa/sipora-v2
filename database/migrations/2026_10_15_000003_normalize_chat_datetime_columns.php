<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Upgrade installations that already ran the initial chat migration.
    // Values and microseconds are retained; fresh installations already use datetime(6).
    public function up(): void
    {
        foreach (['chat_rooms', 'chat_messages', 'chat_message_reports'] as $name) {
            Schema::table($name, function (Blueprint $table) use ($name): void {
                $table->dateTime('created_at', 6)->nullable()->change();
                $table->dateTime('updated_at', 6)->nullable()->change();
                if ($name === 'chat_messages') {
                    $table->dateTime('deleted_at', 6)->nullable()->change();
                }
            });
        }
    }

    public function down(): void
    {
        // Keep the canonical datetime(6) representation when rolling back the upgrade.
        // The preceding create-chat migration removes the tables if fully rolled back.
    }
};
