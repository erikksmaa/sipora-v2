<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['permissions', 'roles'] as $name) {
            Schema::create($name, function (Blueprint $table): void {
                $table->id();
                $table->string('name', 125);
                $table->string('guard_name', 125);
                $table->dateTime('created_at', 6)->nullable();
                $table->dateTime('updated_at', 6)->nullable();
                $table->unique(['name', 'guard_name']);
            });
        }

        foreach (['permission', 'role'] as $entity) {
            Schema::create('model_has_'.$entity.'s', function (Blueprint $table) use ($entity): void {
                $table->foreignId($entity.'_id')->constrained($entity.'s')->cascadeOnDelete();
                $table->string('model_type', 125);
                $table->binary('model_id', 16, true);
                $table->index(['model_id', 'model_type']);
                $table->primary([$entity.'_id', 'model_id', 'model_type']);
            });
        }

        Schema::create('role_has_permissions', function (Blueprint $table): void {
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->primary(['permission_id', 'role_id']);
        });
    }

    public function down(): void
    {
        foreach (['role_has_permissions', 'model_has_roles', 'model_has_permissions', 'roles', 'permissions'] as $name) {
            Schema::dropIfExists($name);
        }
    }
};
