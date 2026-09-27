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
        Schema::create('skills', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->string('name', 120);
            $table->string('slug', 190)->unique('uq_skills_slug');
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
        });

        Schema::create('user_skills', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('user_id', 16, true);
            $table->binary('skill_id', 16, true);
            $table->string('proficiency_level', 24)->nullable();
            $table->boolean('is_self_reported')->default(true);
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('skill_id')->references('id')->on('skills')->restrictOnDelete()->restrictOnUpdate();
            $table->unique(['user_id', 'skill_id'], 'uq_user_skills');
            $table->index(['skill_id', 'deleted_at'], 'idx_user_skills_skill');
        });

        Schema::create('user_educations', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('user_id', 16, true);
            $table->string('education_level', 40);
            $table->string('institution_name', 180);
            $table->string('field_of_study', 180)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->text('description')->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
            $table->index(['user_id', 'deleted_at', 'start_date'], 'idx_user_educations_user');
        });

        Schema::create('organization_experiences', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('user_id', 16, true);
            $table->string('organization_name', 180);
            $table->string('role_title', 160)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->text('description')->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
            $table->index(['user_id', 'deleted_at', 'start_date'], 'idx_org_exp_user');
        });

        Schema::create('user_achievements', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('user_id', 16, true);
            $table->string('title', 180);
            $table->string('issuer_name', 180)->nullable();
            $table->date('achievement_date')->nullable();
            $table->text('description')->nullable();
            $table->text('evidence_path')->nullable();
            $table->string('verification_status', 24)->default('self_reported');
            $table->binary('verified_by', 16, true)->nullable();
            $table->dateTime('verified_at', 6)->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('verified_by')->references('id')->on('users')->nullOnDelete()->restrictOnUpdate();
            $table->index(['user_id', 'deleted_at'], 'idx_user_achievements_user');
        });

        Schema::create('user_identity_verifications', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('user_identity_id', 16, true);
            $table->string('document_type', 24);
            $table->char('document_number_hash', 64)->nullable();
            $table->binary('document_number_ciphertext', 512)->nullable();
            $table->text('document_path');
            $table->char('document_sha256', 64)->nullable();
            $table->string('status', 24)->default('pending');
            $table->dateTime('submitted_at', 6)->useCurrent();
            $table->dateTime('reviewed_at', 6)->nullable();
            $table->binary('reviewed_by', 16, true)->nullable();
            $table->text('review_notes')->nullable();
            $table->json('metadata')->default(new Expression('(JSON_OBJECT())'));
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('user_identity_id')->references('id')->on('user_identities')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete()->restrictOnUpdate();
            $table->index(['status', 'deleted_at', 'submitted_at'], 'idx_uiv_status_submitted');
        });

        DB::statement("ALTER TABLE user_skills ADD CONSTRAINT ck_user_skills_level CHECK (proficiency_level IS NULL OR proficiency_level IN ('beginner','intermediate','advanced','expert'))");
        DB::statement('ALTER TABLE user_educations ADD CONSTRAINT ck_user_educations_dates CHECK (end_date IS NULL OR start_date IS NULL OR end_date >= start_date), ADD CONSTRAINT ck_user_educations_current CHECK (NOT is_current OR end_date IS NULL)');
        DB::statement('ALTER TABLE organization_experiences ADD CONSTRAINT ck_org_exp_dates CHECK (end_date IS NULL OR start_date IS NULL OR end_date >= start_date), ADD CONSTRAINT ck_org_exp_current CHECK (NOT is_current OR end_date IS NULL)');
        DB::statement("ALTER TABLE user_achievements ADD CONSTRAINT ck_user_achievements_status CHECK (verification_status IN ('self_reported','pending','verified','rejected'))");
        DB::statement("ALTER TABLE user_identity_verifications ADD CONSTRAINT ck_uiv_document_type CHECK (document_type IN ('ktp','kia','student_card')), ADD CONSTRAINT ck_uiv_status CHECK (status IN ('pending','revision','rejected','verified')), ADD CONSTRAINT ck_uiv_review_state CHECK (status = 'pending' OR reviewed_at IS NOT NULL), ADD CONSTRAINT ck_uiv_metadata_object CHECK (JSON_TYPE(metadata) = 'OBJECT')");
    }

    public function down(): void
    {
        Schema::dropIfExists('user_identity_verifications');
        Schema::dropIfExists('user_achievements');
        Schema::dropIfExists('organization_experiences');
        Schema::dropIfExists('user_educations');
        Schema::dropIfExists('user_skills');
        Schema::dropIfExists('skills');
    }
};
