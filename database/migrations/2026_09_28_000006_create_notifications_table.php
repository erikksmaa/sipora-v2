<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('user_id', 16, true);
            $table->string('notification_type', 80);
            $table->string('title', 220);
            $table->text('body');
            $table->json('data')->default(new Expression('(JSON_OBJECT())'));
            $table->dateTime('read_at', 6)->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
            $table->index(['user_id', 'deleted_at', 'created_at'], 'idx_notifications_user_created');
            $table->index(['user_id', 'read_at', 'deleted_at', 'created_at'], 'idx_notifications_unread');
        });

        DB::statement("ALTER TABLE notifications ADD CONSTRAINT ck_notifications_data_object CHECK (JSON_TYPE(data) = 'OBJECT')");
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
