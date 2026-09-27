<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('administrative_areas', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('parent_id', 16, true)->nullable();
            $table->string('code', 32)->unique();
            $table->string('name', 160);
            $table->string('area_level', 24);
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('parent_id')->references('id')->on('administrative_areas')->restrictOnDelete()->restrictOnUpdate();
            $table->index(['parent_id', 'deleted_at'], 'idx_administrative_areas_parent');
            $table->index(['area_level', 'name', 'deleted_at'], 'idx_administrative_areas_level_name');
        });

        Schema::create('user_profiles', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('user_id', 16, true)->unique();
            $table->string('public_slug', 190)->unique();
            $table->string('full_name', 160);
            $table->string('birth_place', 120)->nullable();
            $table->date('birth_date');
            $table->string('gender', 24)->nullable();
            $table->string('phone', 32)->nullable();
            $table->text('bio')->nullable();
            $table->string('occupation_status', 32)->nullable();
            $table->string('occupation_title', 160)->nullable();
            $table->text('profile_photo_path')->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
        });

        Schema::create('user_addresses', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('user_id', 16, true);
            $table->binary('administrative_area_id', 16, true);
            $table->string('address_type', 24)->default('domicile');
            $table->text('address_line')->nullable();
            $table->string('rt', 4)->nullable();
            $table->string('rw', 4)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->boolean('is_primary')->default(true);
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('administrative_area_id')->references('id')->on('administrative_areas')->restrictOnDelete()->restrictOnUpdate();
            $table->index(['administrative_area_id', 'deleted_at'], 'idx_user_addresses_area');
        });

        Schema::create('interests', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->string('name', 120);
            $table->string('slug', 190)->unique();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
        });

        Schema::create('user_interests', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('user_id', 16, true);
            $table->binary('interest_id', 16, true);
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('interest_id')->references('id')->on('interests')->restrictOnDelete()->restrictOnUpdate();
            $table->unique(['user_id', 'interest_id'], 'uq_user_interests');
            $table->index(['interest_id', 'deleted_at'], 'idx_user_interests_interest');
        });

        Schema::create('user_identities', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('user_id', 16, true)->unique();
            $table->char('national_id_hash', 64)->nullable()->unique();
            $table->binary('national_id_ciphertext', 512)->nullable();
            $table->string('verification_status', 24)->default('unverified');
            $table->string('verification_method', 24)->nullable();
            $table->dateTime('verified_at', 6)->nullable();
            $table->binary('verified_by', 16, true)->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('verified_by')->references('id')->on('users')->nullOnDelete()->restrictOnUpdate();
        });

        Schema::create('user_profile_visibility', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('user_id', 16, true)->unique();
            $table->boolean('is_profile_public')->default(true);
            $table->boolean('show_photo')->default(true);
            $table->boolean('show_bio')->default(true);
            $table->boolean('show_interests')->default(true);
            $table->boolean('show_skills')->default(true);
            $table->boolean('show_education')->default(true);
            $table->boolean('show_organization_experience')->default(true);
            $table->boolean('show_community_membership')->default(true);
            $table->boolean('show_activity_passport')->default(true);
            $table->boolean('show_certificates')->default(true);
            $table->boolean('show_achievements')->default(true);
            $table->boolean('show_business_experience')->default(true);
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
        });

        DB::statement("ALTER TABLE administrative_areas ADD CONSTRAINT ck_admin_areas_level CHECK (area_level IN ('province','regency','city','district','village'))");
        DB::statement("ALTER TABLE user_profiles ADD CONSTRAINT ck_user_profiles_name CHECK (CHAR_LENGTH(TRIM(full_name)) >= 2), ADD CONSTRAINT ck_user_profiles_birth_date CHECK (birth_date >= '1900-01-01'), ADD CONSTRAINT ck_user_profiles_gender CHECK (gender IS NULL OR gender IN ('male','female','other','prefer_not_to_say')), ADD CONSTRAINT ck_user_profiles_occupation CHECK (occupation_status IS NULL OR occupation_status IN ('student','university_student','worker','entrepreneur','unemployed','other'))");
        DB::statement("ALTER TABLE user_addresses ADD CONSTRAINT ck_user_addresses_type CHECK (address_type IN ('domicile','identity','other'))");
        DB::statement("ALTER TABLE user_identities ADD CONSTRAINT ck_user_identities_status CHECK (verification_status IN ('unverified','pending','revision','rejected','verified')), ADD CONSTRAINT ck_user_identities_method CHECK (verification_method IS NULL OR verification_method IN ('ktp','kia','student_card'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profile_visibility');
        Schema::dropIfExists('user_identities');
        Schema::dropIfExists('user_interests');
        Schema::dropIfExists('interests');
        Schema::dropIfExists('user_addresses');
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('administrative_areas');
    }
};
