<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_reports', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('program_id', 16, true);
            $table->integer('version')->default(1);
            $table->string('status', 24)->default('draft');
            $table->text('notes')->nullable();
            $table->dateTime('submitted_at', 6)->nullable();
            $table->dateTime('reviewed_at', 6)->nullable();
            $table->binary('reviewed_by', 16, true)->nullable();
            $table->text('review_notes')->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('program_id', 'fk_financial_reports_program')->references('id')->on('programs')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('reviewed_by', 'fk_financial_reports_reviewer')->references('id')->on('users')->nullOnDelete()->restrictOnUpdate();
            $table->unique(['program_id', 'version'], 'uq_financial_reports_version');
            $table->index(['status', 'deleted_at', 'submitted_at'], 'idx_financial_reports_queue');
        });
        DB::statement("ALTER TABLE financial_reports ROW_FORMAT=DYNAMIC, ADD INDEX idx_financial_reports_program_latest (program_id, deleted_at, version DESC), ADD CONSTRAINT ck_financial_reports_version CHECK (version > 0), ADD CONSTRAINT ck_financial_reports_status CHECK (status IN ('draft','submitted','under_review','revision','rejected','approved')), ADD CONSTRAINT ck_financial_reports_submitted CHECK (status = 'draft' OR submitted_at IS NOT NULL)");

        Schema::create('financial_items', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('financial_report_id', 16, true);
            $table->string('transaction_type', 16);
            $table->date('transaction_date');
            $table->text('description');
            $table->decimal('amount', 18, 2);
            $table->text('receipt_path')->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('financial_report_id', 'fk_financial_items_report')->references('id')->on('financial_reports')->cascadeOnDelete()->restrictOnUpdate();
            $table->index(['financial_report_id', 'deleted_at', 'transaction_date'], 'idx_financial_items_report_date');
        });
        DB::statement("ALTER TABLE financial_items ROW_FORMAT=DYNAMIC, ADD CONSTRAINT ck_financial_items_type CHECK (transaction_type IN ('income','expense')), ADD CONSTRAINT ck_financial_items_amount CHECK (amount > 0)");
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_items');
        Schema::dropIfExists('financial_reports');
    }
};
