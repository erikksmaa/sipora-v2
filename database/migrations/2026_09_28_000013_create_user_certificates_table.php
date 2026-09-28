<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_certificates', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('user_id', 16, true);
            $table->binary('participation_id', 16, true)->nullable();
            $table->string('source_type', 24);
            $table->string('name', 220);
            $table->string('issuer_name', 180);
            $table->string('certificate_number', 120)->nullable();
            $table->string('verification_code', 120)->nullable();
            $table->date('issued_at');
            $table->date('expires_at')->nullable();
            $table->text('file_path')->nullable();
            $table->text('external_url')->nullable();
            $table->string('verification_status', 24)->default('self_reported');
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('participation_id')->references('id')->on('activity_participations')->restrictOnDelete()->restrictOnUpdate();
            $table->unique('verification_code', 'uq_user_certificates_verification_code');
            $table->index(['user_id', 'deleted_at', 'issued_at'], 'idx_user_certificates_user');
        });

        DB::statement("ALTER TABLE user_certificates ADD CONSTRAINT ck_user_certificates_source CHECK (source_type IN ('sipora','external')), ADD CONSTRAINT ck_user_certificates_status CHECK (verification_status IN ('self_reported','pending','verified','rejected')), ADD CONSTRAINT ck_user_certificates_dates CHECK (expires_at IS NULL OR expires_at >= issued_at)");
    }

    public function down(): void
    {
        Schema::dropIfExists('user_certificates');
    }
};
