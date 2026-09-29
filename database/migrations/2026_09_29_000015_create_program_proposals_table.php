<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_proposals', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('program_id', 16, true);
            $table->integer('version');
            $table->string('status', 24)->default('draft');
            $table->decimal('requested_budget', 18, 2)->nullable();
            $table->text('proposal_document_path')->nullable();
            $table->dateTime('submitted_at', 6)->nullable();
            $table->dateTime('reviewed_at', 6)->nullable();
            $table->binary('reviewed_by', 16, true)->nullable();
            $table->text('review_notes')->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('program_id', 'fk_program_proposals_program')->references('id')->on('programs')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('reviewed_by', 'fk_program_proposals_reviewer')->references('id')->on('users')->nullOnDelete()->restrictOnUpdate();
            $table->unique(['program_id', 'version'], 'uq_program_proposals_version');
            $table->index(['status', 'deleted_at', 'submitted_at'], 'idx_program_proposals_queue');
        });

        DB::statement("ALTER TABLE program_proposals ROW_FORMAT=DYNAMIC, ADD INDEX idx_program_proposals_program_latest (program_id, deleted_at, version DESC), ADD CONSTRAINT ck_program_proposals_version CHECK (version > 0), ADD CONSTRAINT ck_program_proposals_budget CHECK (requested_budget IS NULL OR requested_budget >= 0), ADD CONSTRAINT ck_program_proposals_status CHECK (status IN ('draft','submitted','under_review','revision','rejected','approved')), ADD CONSTRAINT ck_program_proposals_submitted CHECK (status = 'draft' OR submitted_at IS NOT NULL), ADD CONSTRAINT ck_program_proposals_reviewed CHECK (status NOT IN ('revision','rejected','approved') OR reviewed_at IS NOT NULL)");
    }

    public function down(): void
    {
        Schema::dropIfExists('program_proposals');
    }
};
