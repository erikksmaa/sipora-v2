<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_categories', function (Blueprint $table): void {
            $table->binary('id', 16, true)->primary();
            $table->string('name', 120);
            $table->string('slug', 120)->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
        });

        Schema::create('forum_threads', function (Blueprint $table): void {
            $table->binary('id', 16, true)->primary();
            $table->binary('category_id', 16, true);
            $table->binary('author_id', 16, true);
            $table->string('title', 180);
            $table->text('body');
            $table->string('status', 16)->default('active');
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->foreign('category_id')->references('id')->on('forum_categories')->restrictOnDelete();
            $table->foreign('author_id')->references('id')->on('users')->restrictOnDelete();
            $table->index(['status', 'created_at']);
            $table->index(['category_id', 'status', 'created_at']);
        });

        Schema::create('forum_replies', function (Blueprint $table): void {
            $table->binary('id', 16, true)->primary();
            $table->binary('thread_id', 16, true);
            $table->binary('author_id', 16, true);
            $table->text('body');
            $table->boolean('is_hidden')->default(false);
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->foreign('thread_id')->references('id')->on('forum_threads')->cascadeOnDelete();
            $table->foreign('author_id')->references('id')->on('users')->restrictOnDelete();
            $table->index(['thread_id', 'is_hidden', 'created_at']);
        });

        Schema::create('forum_reactions', function (Blueprint $table): void {
            $table->binary('id', 16, true)->primary();
            $table->binary('thread_id', 16, true);
            $table->binary('user_id', 16, true);
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->foreign('thread_id')->references('id')->on('forum_threads')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unique(['thread_id', 'user_id']);
        });

        Schema::create('forum_reports', function (Blueprint $table): void {
            $table->binary('id', 16, true)->primary();
            $table->binary('thread_id', 16, true);
            $table->binary('reply_id', 16, true)->nullable();
            $table->binary('reporter_id', 16, true);
            $table->string('reason', 32);
            $table->text('details')->nullable();
            $table->string('status', 16)->default('pending');
            $table->binary('resolved_by', 16, true)->nullable();
            $table->dateTime('resolved_at', 6)->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->foreign('thread_id')->references('id')->on('forum_threads')->cascadeOnDelete();
            $table->foreign('reply_id')->references('id')->on('forum_replies')->cascadeOnDelete();
            $table->foreign('reporter_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('resolved_by')->references('id')->on('users')->nullOnDelete();
            $table->index(['status', 'created_at']);
            $table->index(['reporter_id', 'thread_id', 'reply_id', 'status'], 'forum_report_duplicate_lookup');
        });
    }

    public function down(): void
    {
        foreach (['forum_reports', 'forum_reactions', 'forum_replies', 'forum_threads', 'forum_categories'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
