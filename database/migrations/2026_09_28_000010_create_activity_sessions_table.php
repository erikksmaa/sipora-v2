<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_sessions', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('activity_id', 16, true);
            $table->unsignedSmallInteger('session_number');
            $table->string('title', 180);
            $table->text('description')->nullable();
            $table->dateTime('start_at', 6);
            $table->dateTime('end_at', 6);
            $table->string('venue_name', 180)->nullable();
            $table->text('address_text')->nullable();
            $table->text('meeting_url')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();

            $table->foreign('activity_id')->references('id')->on('activities')->cascadeOnDelete()->restrictOnUpdate();
            $table->unique(['activity_id', 'session_number'], 'uq_activity_sessions_number');
            $table->index(['activity_id', 'deleted_at', 'start_at'], 'idx_activity_sessions_activity_start');
        });

        DB::statement('ALTER TABLE activity_sessions ADD CONSTRAINT ck_activity_sessions_number CHECK (session_number > 0), ADD CONSTRAINT ck_activity_sessions_dates CHECK (end_at > start_at)');
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_sessions');
    }
};
