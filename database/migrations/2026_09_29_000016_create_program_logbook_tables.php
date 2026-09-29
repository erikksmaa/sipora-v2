<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_logbooks', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('program_id', 16, true);
            $table->binary('activity_id', 16, true)->nullable();
            $table->binary('created_by_user_id', 16, true);
            $table->date('log_date');
            $table->text('summary');
            $table->text('obstacles')->nullable();
            $table->text('solutions')->nullable();
            $table->smallInteger('progress_percent')->default(0);
            $table->string('status', 24)->default('draft');
            $table->dateTime('submitted_at', 6)->nullable();
            $table->dateTime('reviewed_at', 6)->nullable();
            $table->binary('reviewed_by', 16, true)->nullable();
            $table->text('review_notes')->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('program_id', 'fk_program_logbooks_program')->references('id')->on('programs')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('activity_id', 'fk_program_logbooks_activity')->references('id')->on('activities')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('created_by_user_id', 'fk_program_logbooks_creator')->references('id')->on('users')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('reviewed_by', 'fk_program_logbooks_reviewer')->references('id')->on('users')->nullOnDelete()->restrictOnUpdate();
            $table->index(['status', 'deleted_at', 'submitted_at'], 'idx_program_logbooks_queue');
        });
        DB::statement("ALTER TABLE program_logbooks ROW_FORMAT=DYNAMIC, ADD INDEX idx_program_logbooks_program_date (program_id, deleted_at, log_date DESC, created_at DESC), ADD CONSTRAINT ck_program_logbooks_progress CHECK (progress_percent BETWEEN 0 AND 100), ADD CONSTRAINT ck_program_logbooks_status CHECK (status IN ('draft','submitted','revision','approved')), ADD CONSTRAINT ck_program_logbooks_submitted CHECK (status = 'draft' OR submitted_at IS NOT NULL)");

        Schema::create('program_logbook_media', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('logbook_id', 16, true);
            $table->binary('uploaded_by', 16, true);
            $table->text('file_path');
            $table->text('caption')->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('logbook_id', 'fk_logbook_media_logbook')->references('id')->on('program_logbooks')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('uploaded_by', 'fk_logbook_media_uploader')->references('id')->on('users')->restrictOnDelete()->restrictOnUpdate();
            $table->index(['logbook_id', 'deleted_at'], 'idx_logbook_media_logbook');
        });
        DB::statement('ALTER TABLE program_logbook_media ROW_FORMAT=DYNAMIC');
    }

    public function down(): void
    {
        Schema::dropIfExists('program_logbook_media');
        Schema::dropIfExists('program_logbooks');
    }
};
