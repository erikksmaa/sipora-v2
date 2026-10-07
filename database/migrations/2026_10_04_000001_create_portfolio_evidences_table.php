<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_evidences', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('user_id', 16, true);
            $table->string('record_type', 24);
            $table->binary('record_id', 16, true);
            $table->string('original_name', 255);
            $table->string('mime_type', 100);
            $table->unsignedInteger('size_bytes');
            $table->text('file_path');
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
            $table->unique(['record_type', 'record_id'], 'uq_portfolio_evidences_record');
            $table->index(['user_id', 'record_type'], 'idx_portfolio_evidences_owner');
        });
        DB::statement("ALTER TABLE portfolio_evidences ADD CONSTRAINT ck_portfolio_evidences_type CHECK (record_type IN ('education','organization'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_evidences');
    }
};
