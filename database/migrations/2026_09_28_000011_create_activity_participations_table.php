<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_participations', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('activity_id', 16, true);
            $table->binary('user_id', 16, true);
            $table->string('activity_role', 24)->default('participant');
            $table->string('registration_status', 24)->default('pending');
            $table->string('completion_status', 24)->default('pending');
            $table->text('registration_notes')->nullable();
            $table->dateTime('requested_at', 6)->useCurrent();
            $table->dateTime('reviewed_at', 6)->nullable();
            $table->binary('reviewed_by', 16, true)->nullable();
            $table->dateTime('completed_at', 6)->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();

            $table->foreign('activity_id')->references('id')->on('activities')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete()->restrictOnUpdate();
            $table->unique(['activity_id', 'user_id'], 'uq_activity_participations');
            $table->index(['activity_id', 'registration_status', 'completion_status', 'deleted_at'], 'idx_activity_participation_activity_status');
        });

        DB::statement("ALTER TABLE activity_participations ADD CONSTRAINT ck_activity_participation_role CHECK (activity_role IN ('participant','volunteer','committee','speaker','organizer')), ADD CONSTRAINT ck_activity_participation_registration CHECK (registration_status IN ('pending','accepted','rejected','cancelled')), ADD CONSTRAINT ck_activity_participation_completion CHECK (completion_status IN ('pending','completed','no_show')), ADD CONSTRAINT ck_activity_participation_completed_at CHECK (completion_status <> 'completed' OR completed_at IS NOT NULL)");
        DB::statement('CREATE INDEX idx_activity_participation_user ON activity_participations(user_id, deleted_at, requested_at DESC)');
        Schema::table('activity_participations', function (Blueprint $table): void {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_participations');
    }
};
