<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_categories', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->string('name', 120);
            $table->string('slug', 190)->unique('uq_activity_categories_slug');
            $table->text('description')->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
        });

        Schema::create('activities', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('organization_id', 16, true);
            // The approved nullable FK target (`programs`) belongs to a later phase.
            // Keep its binary column now; that phase will add the constraint after creating the target table.
            $table->binary('program_id', 16, true)->nullable();
            $table->binary('category_id', 16, true);
            $table->binary('created_by_user_id', 16, true);
            $table->string('title', 220);
            $table->string('slug', 190)->unique('uq_activities_slug');
            $table->text('description')->nullable();
            $table->text('poster_path')->nullable();
            $table->string('location_type', 16)->default('offline');
            $table->string('venue_name', 180)->nullable();
            $table->binary('administrative_area_id', 16, true)->nullable();
            $table->text('address_text')->nullable();
            $table->text('meeting_url')->nullable();
            $table->dateTime('start_at', 6);
            $table->dateTime('end_at', 6);
            $table->dateTime('registration_open_at', 6)->nullable();
            $table->dateTime('registration_close_at', 6)->nullable();
            $table->integer('quota')->nullable();
            $table->string('registration_mode', 24)->default('open');
            $table->smallInteger('min_age')->nullable();
            $table->smallInteger('max_age')->nullable();
            $table->boolean('requires_identity_verification')->default(false);
            $table->boolean('members_only')->default(false);
            $table->text('eligibility_notes')->nullable();
            $table->boolean('certificate_enabled')->default(false);
            $table->string('review_status', 24)->default('draft');
            $table->string('publication_status', 24)->default('unpublished');
            $table->string('execution_status', 24)->default('scheduled');
            $table->dateTime('published_at', 6)->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('organization_id')->references('id')->on('organizations')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('category_id')->references('id')->on('activity_categories')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('created_by_user_id')->references('id')->on('users')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('administrative_area_id')->references('id')->on('administrative_areas')->nullOnDelete()->restrictOnUpdate();
            $table->index(['organization_id', 'execution_status', 'deleted_at'], 'idx_activities_org_status');
            $table->index(['program_id', 'deleted_at'], 'idx_activities_program');
            $table->index(['category_id', 'deleted_at', 'start_at'], 'idx_activities_category_start');
            $table->index(['publication_status', 'review_status', 'execution_status', 'deleted_at', 'start_at'], 'idx_activities_public_upcoming');
            $table->index(['administrative_area_id', 'deleted_at', 'start_at'], 'idx_activities_area_start');
        });

        Schema::create('activity_reviews', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('activity_id', 16, true);
            $table->binary('reviewer_id', 16, true);
            $table->string('decision', 24);
            $table->text('review_notes')->nullable();
            $table->dateTime('reviewed_at', 6)->useCurrent();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('activity_id')->references('id')->on('activities')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('reviewer_id')->references('id')->on('users')->restrictOnDelete()->restrictOnUpdate();
            $table->index(['activity_id', 'deleted_at', 'reviewed_at'], 'idx_activity_reviews_activity_date');
        });

        DB::statement("ALTER TABLE activities ADD CONSTRAINT ck_activities_location_type CHECK (location_type IN ('offline','online','hybrid')), ADD CONSTRAINT ck_activities_dates CHECK (end_at > start_at), ADD CONSTRAINT ck_activities_registration_dates CHECK (registration_close_at IS NULL OR registration_open_at IS NULL OR registration_close_at >= registration_open_at), ADD CONSTRAINT ck_activities_quota CHECK (quota IS NULL OR quota > 0), ADD CONSTRAINT ck_activities_registration_mode CHECK (registration_mode IN ('open','approval_required')), ADD CONSTRAINT ck_activities_age CHECK ((min_age IS NULL OR min_age BETWEEN 0 AND 100) AND (max_age IS NULL OR max_age BETWEEN 0 AND 100) AND (min_age IS NULL OR max_age IS NULL OR max_age >= min_age)), ADD CONSTRAINT ck_activities_review_status CHECK (review_status IN ('draft','pending_review','revision','rejected','approved')), ADD CONSTRAINT ck_activities_publication_status CHECK (publication_status IN ('unpublished','published','archived')), ADD CONSTRAINT ck_activities_execution_status CHECK (execution_status IN ('scheduled','ongoing','completed','cancelled')), ADD CONSTRAINT ck_activities_publish_state CHECK (publication_status <> 'published' OR (review_status = 'approved' AND published_at IS NOT NULL))");
        DB::statement("ALTER TABLE activity_reviews ADD CONSTRAINT ck_activity_reviews_decision CHECK (decision IN ('approved','revision','rejected'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_reviews');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('activity_categories');
    }
};
