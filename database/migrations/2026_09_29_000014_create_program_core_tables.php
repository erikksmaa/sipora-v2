<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_categories', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->string('name', 120);
            $table->string('slug', 190)->unique('uq_program_categories_slug');
            $table->text('description')->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
        });

        Schema::create('programs', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('organization_id', 16, true);
            $table->binary('category_id', 16, true);
            $table->binary('created_by_user_id', 16, true);
            $table->string('title', 220);
            $table->string('slug', 190)->unique('uq_programs_slug');
            $table->text('description')->nullable();
            $table->text('objectives')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('execution_status', 24)->default('planned');
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('organization_id', 'fk_programs_org')->references('id')->on('organizations')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('category_id', 'fk_programs_category')->references('id')->on('program_categories')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('created_by_user_id', 'fk_programs_creator')->references('id')->on('users')->restrictOnDelete()->restrictOnUpdate();
            $table->index(['organization_id', 'execution_status', 'deleted_at'], 'idx_programs_org_status');
            $table->index(['start_date', 'end_date', 'deleted_at'], 'idx_programs_dates');
        });

        DB::statement("ALTER TABLE programs ADD CONSTRAINT ck_programs_dates CHECK (end_date IS NULL OR start_date IS NULL OR end_date >= start_date), ADD CONSTRAINT ck_programs_execution_status CHECK (execution_status IN ('planned','running','completed','cancelled'))");
        Schema::table('activities', function (Blueprint $table): void {
            $table->foreign('program_id', 'fk_activities_program')->references('id')->on('programs')->restrictOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table): void {
            $table->dropForeign('fk_activities_program');
        });
        Schema::dropIfExists('programs');
        Schema::dropIfExists('program_categories');
    }
};
