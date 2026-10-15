<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_rooms', function (Blueprint $t) {
            $t->binary('id', 16, true)->primary();
            $t->enum('type', ['global', 'community']);
            $t->binary('community_id', 16, true)->nullable()->unique();
            $t->string('name')->nullable();
            $t->boolean('is_active')->default(true);
            $t->dateTime('created_at', 6)->nullable();
            $t->dateTime('updated_at', 6)->nullable();
            $t->foreign('community_id')->references('id')->on('organizations')->restrictOnDelete();
        });
        DB::statement("ALTER TABLE chat_rooms ADD global_slot TINYINT GENERATED ALWAYS AS (CASE WHEN type = 'global' THEN 1 ELSE NULL END) STORED, ADD UNIQUE KEY uq_chat_global (global_slot), ADD CONSTRAINT ck_chat_scope CHECK ((type = 'global' AND community_id IS NULL) OR (type = 'community' AND community_id IS NOT NULL))");
        Schema::create('chat_messages', function (Blueprint $t) {
            $t->binary('id', 16, true)->primary();
            $t->binary('chat_room_id', 16, true);
            $t->binary('user_id', 16, true);
            $t->binary('client_nonce', 16, true);
            $t->text('message');
            $t->dateTime('created_at', 6)->nullable();
            $t->dateTime('updated_at', 6)->nullable();
            $t->dateTime('deleted_at', 6)->nullable();
            $t->foreign('chat_room_id')->references('id')->on('chat_rooms')->restrictOnDelete();
            $t->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $t->unique(['chat_room_id', 'user_id', 'client_nonce'], 'uq_chat_submission');
            $t->index(['chat_room_id', 'created_at', 'id'], 'idx_chat_history');
            $t->index(['chat_room_id', 'updated_at', 'id'], 'idx_chat_changes');
            $t->index('user_id');
        });
        Schema::create('chat_message_reports', function (Blueprint $t) {
            $t->binary('id', 16, true)->primary();
            $t->binary('chat_message_id', 16, true);
            $t->binary('reporter_id', 16, true);
            $t->string('reason', 40);
            $t->enum('status', ['pending', 'dismissed', 'actioned'])->default('pending');
            $t->binary('reviewed_by', 16, true)->nullable();
            $t->dateTime('reviewed_at', 6)->nullable();
            $t->dateTime('created_at', 6)->nullable();
            $t->dateTime('updated_at', 6)->nullable();
            $t->unique(['chat_message_id', 'reporter_id'], 'uq_chat_report');
            $t->index(['status', 'created_at']);
            $t->foreign('chat_message_id')->references('id')->on('chat_messages')->restrictOnDelete();
            $t->foreign('reporter_id')->references('id')->on('users')->restrictOnDelete();
            $t->foreign('reviewed_by')->references('id')->on('users')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_message_reports');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_rooms');
    }
};
