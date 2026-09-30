<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opportunity_categories', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->string('name', 120);
            $table->string('slug', 190)->unique('uq_opportunity_categories_slug');
            $table->text('description')->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
        });

        Schema::create('opportunities', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('category_id', 16, true);
            $table->binary('organization_id', 16, true)->nullable();
            $table->binary('created_by_user_id', 16, true)->nullable();
            $table->string('title', 220);
            $table->string('slug', 190)->unique('uq_opportunities_slug');
            $table->string('provider_name', 180);
            $table->text('description');
            $table->binary('administrative_area_id', 16, true)->nullable();
            $table->string('location_text', 220)->nullable();
            $table->text('external_url');
            $table->dateTime('deadline_at', 6)->nullable();
            $table->dateTime('starts_at', 6)->nullable();
            $table->dateTime('ends_at', 6)->nullable();
            $table->string('publication_status', 24)->default('draft');
            $table->dateTime('published_at', 6)->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('category_id', 'fk_opportunities_category')->references('id')->on('opportunity_categories')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('organization_id', 'fk_opportunities_org')->references('id')->on('organizations')->nullOnDelete()->restrictOnUpdate();
            $table->foreign('created_by_user_id', 'fk_opportunities_creator')->references('id')->on('users')->nullOnDelete()->restrictOnUpdate();
            $table->foreign('administrative_area_id', 'fk_opportunities_area')->references('id')->on('administrative_areas')->nullOnDelete()->restrictOnUpdate();
        });
        DB::statement("ALTER TABLE opportunities ROW_FORMAT=DYNAMIC, ADD INDEX idx_opportunities_category_deadline (category_id, deleted_at, deadline_at), ADD INDEX idx_opportunities_published_deadline (publication_status, deleted_at, deadline_at), ADD CONSTRAINT ck_opportunities_dates CHECK (ends_at IS NULL OR starts_at IS NULL OR ends_at >= starts_at), ADD CONSTRAINT ck_opportunities_status CHECK (publication_status IN ('draft','published','archived')), ADD CONSTRAINT ck_opportunities_published CHECK (publication_status <> 'published' OR published_at IS NOT NULL)");

        Schema::create('opportunity_bookmarks', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('user_id', 16, true);
            $table->binary('opportunity_id', 16, true);
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->unique(['user_id', 'opportunity_id'], 'uq_opportunity_bookmarks');
            $table->foreign('user_id', 'fk_opportunity_bookmark_user')->references('id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('opportunity_id', 'fk_opportunity_bookmark_opportunity')->references('id')->on('opportunities')->cascadeOnDelete()->restrictOnUpdate();
        });
        DB::statement('ALTER TABLE opportunity_categories ROW_FORMAT=DYNAMIC');
        DB::statement('ALTER TABLE opportunity_bookmarks ROW_FORMAT=DYNAMIC');
    }

    public function down(): void
    {
        Schema::dropIfExists('opportunity_bookmarks');
        Schema::dropIfExists('opportunities');
        Schema::dropIfExists('opportunity_categories');
    }
};
