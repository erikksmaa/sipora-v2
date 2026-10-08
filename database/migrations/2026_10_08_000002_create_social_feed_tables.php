<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_posts', function (Blueprint $table): void {
            $table->binary('id', 16, true)->primary();
            $table->binary('author_id', 16, true);
            $table->binary('organization_id', 16, true)->nullable();
            $table->text('body');
            $table->string('image_path')->nullable();
            $table->string('visibility', 16)->default('public');
            $table->boolean('is_hidden')->default(false);
            $table->boolean('is_removed')->default(false);
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->foreign('author_id')->references('id')->on('users')->restrictOnDelete();
            $table->foreign('organization_id')->references('id')->on('organizations')->restrictOnDelete();
            $table->index(['visibility', 'is_hidden', 'is_removed', 'created_at'], 'social_public_timeline');
            $table->index(['author_id', 'created_at']);
            $table->index(['organization_id', 'created_at']);
        });
        Schema::create('social_comments', function (Blueprint $table): void {
            $table->binary('id', 16, true)->primary();
            $table->binary('post_id', 16, true);
            $table->binary('author_id', 16, true);
            $table->text('body');
            $table->boolean('is_hidden')->default(false);
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->foreign('post_id')->references('id')->on('social_posts')->cascadeOnDelete();
            $table->foreign('author_id')->references('id')->on('users')->restrictOnDelete();
            $table->index(['post_id', 'is_hidden', 'created_at']);
        });
        Schema::create('social_likes', function (Blueprint $table): void {
            $table->binary('id', 16, true)->primary();
            $table->binary('post_id', 16, true);
            $table->binary('user_id', 16, true);
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->foreign('post_id')->references('id')->on('social_posts')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unique(['post_id', 'user_id']);
        });
        // Two explicit nullable FKs keep each follow tied to a real domain target.
        Schema::create('social_follows', function (Blueprint $table): void {
            $table->binary('id', 16, true)->primary();
            $table->binary('user_id', 16, true);
            $table->binary('youth_id', 16, true)->nullable();
            $table->binary('organization_id', 16, true)->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('youth_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
            $table->unique(['user_id', 'youth_id']);
            $table->unique(['user_id', 'organization_id']);
        });
        Schema::create('social_reports', function (Blueprint $table): void {
            $table->binary('id', 16, true)->primary();
            $table->binary('post_id', 16, true);
            $table->binary('comment_id', 16, true)->nullable();
            $table->binary('reporter_id', 16, true);
            $table->string('reason', 32);
            $table->text('details')->nullable();
            $table->string('status', 16)->default('pending');
            $table->binary('resolved_by', 16, true)->nullable();
            $table->dateTime('resolved_at', 6)->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->foreign('post_id')->references('id')->on('social_posts')->cascadeOnDelete();
            $table->foreign('comment_id')->references('id')->on('social_comments')->cascadeOnDelete();
            $table->foreign('reporter_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('resolved_by')->references('id')->on('users')->nullOnDelete();
            $table->index(['status', 'created_at']);
            $table->index(['reporter_id', 'post_id', 'comment_id', 'status'], 'social_report_duplicate_lookup');
        });
    }

    public function down(): void
    {
        foreach (['social_reports', 'social_follows', 'social_likes', 'social_comments', 'social_posts'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
