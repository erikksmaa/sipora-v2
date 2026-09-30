<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_evaluations', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('program_id', 16, true);
            $table->binary('verifier_id', 16, true);
            $table->string('decision', 24);
            $table->text('evaluation_notes')->nullable();
            $table->dateTime('evaluated_at', 6)->useCurrent();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('program_id', 'fk_program_evaluations_program')->references('id')->on('programs')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('verifier_id', 'fk_program_evaluations_verifier')->references('id')->on('users')->restrictOnDelete()->restrictOnUpdate();
        });
        DB::statement("ALTER TABLE program_evaluations ROW_FORMAT=DYNAMIC, ADD INDEX idx_program_evaluations_program_date (program_id, deleted_at, evaluated_at DESC), ADD CONSTRAINT ck_program_evaluations_decision CHECK (decision IN ('approved','revision','rejected'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('program_evaluations');
    }
};
