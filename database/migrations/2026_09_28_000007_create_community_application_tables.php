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
        Schema::create('organization_categories', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->string('name', 120);
            $table->string('slug', 190)->unique('uq_organization_categories_slug');
            $table->text('description')->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
        });

        Schema::create('organizations', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('created_by_user_id', 16, true)->nullable();
            $table->binary('category_id', 16, true);
            $table->binary('administrative_area_id', 16, true)->nullable();
            $table->string('name', 180);
            $table->string('slug', 190)->unique('uq_organizations_slug');
            $table->text('description')->nullable();
            $table->text('logo_path')->nullable();
            $table->string('contact_email', 254)->nullable();
            $table->string('contact_phone', 32)->nullable();
            $table->text('website_url')->nullable();
            $table->text('address_text')->nullable();
            $table->json('social_links')->default(new Expression('(JSON_OBJECT())'));
            $table->string('review_status', 24)->default('draft');
            $table->string('operational_status', 24)->default('inactive');
            $table->dateTime('approved_at', 6)->nullable();
            $table->binary('approved_by', 16, true)->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('created_by_user_id')->references('id')->on('users')->nullOnDelete()->restrictOnUpdate();
            $table->foreign('category_id')->references('id')->on('organization_categories')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('administrative_area_id')->references('id')->on('administrative_areas')->nullOnDelete()->restrictOnUpdate();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete()->restrictOnUpdate();
            $table->index(['review_status', 'operational_status', 'deleted_at'], 'idx_organizations_review_operational');
        });

        Schema::create('organization_verification_requests', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('organization_id', 16, true);
            $table->binary('submitted_by', 16, true);
            $table->string('status', 24)->default('pending');
            $table->text('submission_notes')->nullable();
            $table->dateTime('submitted_at', 6)->useCurrent();
            $table->binary('reviewed_by', 16, true)->nullable();
            $table->dateTime('reviewed_at', 6)->nullable();
            $table->text('review_notes')->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('submitted_by')->references('id')->on('users')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete()->restrictOnUpdate();
            $table->index(['status', 'deleted_at', 'submitted_at'], 'idx_org_verification_queue');
        });

        DB::statement("ALTER TABLE organizations ADD CONSTRAINT ck_organizations_review_status CHECK (review_status IN ('draft','pending_review','revision','rejected','approved')), ADD CONSTRAINT ck_organizations_operational_status CHECK (operational_status IN ('inactive','active','suspended','archived')), ADD CONSTRAINT ck_organizations_social_links CHECK (JSON_TYPE(social_links) = 'OBJECT'), ADD CONSTRAINT ck_organizations_approved CHECK (review_status <> 'approved' OR approved_at IS NOT NULL), ADD CONSTRAINT ck_organizations_active_requires_approval CHECK (operational_status <> 'active' OR review_status = 'approved')");
        DB::statement("ALTER TABLE organization_verification_requests ADD CONSTRAINT ck_org_verify_status CHECK (status IN ('pending','revision','rejected','approved')), ADD CONSTRAINT ck_org_verify_review CHECK (status = 'pending' OR reviewed_at IS NOT NULL)");
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_verification_requests');
        Schema::dropIfExists('organizations');
        Schema::dropIfExists('organization_categories');
    }
};
