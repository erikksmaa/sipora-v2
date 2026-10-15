<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table): void {
            $table->boolean('registration_enabled')->default(false);
            $table->string('selection_mode', 12)->default('open');
            $table->unsignedInteger('quota')->nullable();
            $table->dateTime('registration_open_at', 6)->nullable();
            $table->dateTime('registration_close_at', 6)->nullable();
        });
        DB::statement("ALTER TABLE programs ADD CONSTRAINT ck_program_intake_mode CHECK (selection_mode IN ('open','review')), ADD CONSTRAINT ck_program_intake_quota CHECK (quota IS NULL OR quota > 0), ADD CONSTRAINT ck_program_intake_window CHECK (registration_close_at IS NULL OR registration_open_at IS NULL OR registration_close_at >= registration_open_at)");
        Schema::create('program_applications', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('program_id', 16, true);
            $table->binary('user_id', 16, true);
            $table->string('status', 24)->default('submitted');
            $table->text('review_notes')->nullable();
            $table->binary('reviewed_by', 16, true)->nullable();
            $table->dateTime('reviewed_at', 6)->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->unique(['program_id', 'user_id'], 'uq_program_application_youth');
            $table->index(['program_id', 'status'], 'idx_program_applications_status');
            $table->index('user_id', 'idx_program_applications_youth');
            $table->foreign('program_id')->references('id')->on('programs')->restrictOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->foreign('reviewed_by')->references('id')->on('users')->restrictOnDelete();
        });
        DB::statement("ALTER TABLE program_applications ADD CONSTRAINT ck_program_application_status CHECK (status IN ('submitted','under_review','accepted','waitlisted','rejected','withdrawn'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('program_applications');
        DB::statement('ALTER TABLE programs DROP CHECK ck_program_intake_mode, DROP CHECK ck_program_intake_quota, DROP CHECK ck_program_intake_window');
        Schema::table('programs', fn (Blueprint $table) => $table->dropColumn(['registration_enabled', 'selection_mode', 'quota', 'registration_open_at', 'registration_close_at']));
    }
};
