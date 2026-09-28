<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_attendances', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('activity_session_id', 16, true);
            $table->binary('participation_id', 16, true);
            $table->string('attendance_status', 24);
            $table->dateTime('checked_in_at', 6)->nullable();
            $table->dateTime('checked_out_at', 6)->nullable();
            $table->binary('recorded_by', 16, true);
            $table->text('notes')->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();

            $table->foreign('activity_session_id')->references('id')->on('activity_sessions')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('participation_id')->references('id')->on('activity_participations')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('recorded_by')->references('id')->on('users')->restrictOnDelete()->restrictOnUpdate();
            $table->unique(['activity_session_id', 'participation_id'], 'uq_activity_attendances_session_participation');
            $table->index(['activity_session_id', 'attendance_status', 'deleted_at'], 'idx_activity_attendance_session_status');
            $table->index(['participation_id', 'deleted_at'], 'idx_activity_attendance_participation');
        });

        DB::statement("ALTER TABLE activity_attendances ADD CONSTRAINT ck_activity_attendance_status CHECK (attendance_status IN ('present','absent','excused')), ADD CONSTRAINT ck_activity_attendance_times CHECK (checked_out_at IS NULL OR checked_in_at IS NULL OR checked_out_at >= checked_in_at)");
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_attendances');
    }
};
