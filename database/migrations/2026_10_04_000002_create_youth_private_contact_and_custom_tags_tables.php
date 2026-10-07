<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_contact_links', function (Blueprint $table): void {
            $table->binary('id', 16, true)->primary();
            $table->binary('user_id', 16, true)->unique();
            $table->string('instagram', 160)->nullable();
            $table->string('facebook', 255)->nullable();
            $table->string('linkedin', 255)->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
        });

        Schema::create('user_custom_portfolio_tags', function (Blueprint $table): void {
            $table->binary('id', 16, true)->primary();
            $table->binary('user_id', 16, true);
            $table->string('kind', 12);
            $table->string('name', 120);
            $table->string('normalized_name', 120);
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
            $table->unique(['user_id', 'kind', 'normalized_name'], 'uq_user_custom_portfolio_tags');
            $table->index(['user_id', 'kind'], 'idx_user_custom_portfolio_tags_kind');
        });
        DB::statement("ALTER TABLE user_custom_portfolio_tags ADD CONSTRAINT ck_user_custom_portfolio_tags_kind CHECK (kind IN ('skill','interest'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('user_custom_portfolio_tags');
        Schema::dropIfExists('user_contact_links');
    }
};
