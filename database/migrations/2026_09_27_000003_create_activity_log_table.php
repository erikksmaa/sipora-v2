<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_log', function (Blueprint $table): void {
            $table->id();
            $table->string('log_name')->nullable()->index();
            $table->text('description');
            foreach (['subject', 'causer'] as $relation) {
                $table->string($relation.'_type', 125)->nullable();
                $table->binary($relation.'_id', 16, true)->nullable();
                $table->index([$relation.'_type', $relation.'_id']);
            }
            $table->string('event')->nullable();
            $table->json('attribute_changes')->nullable();
            $table->json('properties')->nullable();
            $table->dateTime('created_at', 6)->nullable();
            $table->dateTime('updated_at', 6)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_log');
    }
};
