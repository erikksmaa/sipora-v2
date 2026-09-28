<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_memberships', function (Blueprint $table): void {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->binary('id', 16, true)->primary();
            $table->binary('organization_id', 16, true);
            $table->binary('user_id', 16, true);
            $table->string('access_role', 24)->default('member');
            $table->string('position_title', 160)->nullable();
            $table->string('membership_status', 24)->default('pending');
            $table->dateTime('requested_at', 6)->useCurrent();
            $table->dateTime('approved_at', 6)->nullable();
            $table->binary('approved_by', 16, true)->nullable();
            $table->dateTime('ended_at', 6)->nullable();
            $table->dateTime('created_at', 6)->useCurrent();
            $table->dateTime('updated_at', 6)->useCurrent();
            $table->dateTime('deleted_at', 6)->nullable();
            $table->foreign('organization_id')->references('id')->on('organizations')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->restrictOnUpdate();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete()->restrictOnUpdate();
            $table->unique(['organization_id', 'user_id'], 'uq_organization_memberships');
            $table->index(['user_id', 'membership_status', 'deleted_at'], 'idx_org_membership_user');
            $table->index(['organization_id', 'membership_status', 'deleted_at'], 'idx_org_membership_org_status');
        });

        DB::statement("ALTER TABLE organization_memberships ADD CONSTRAINT ck_org_membership_role CHECK (access_role IN ('leader','manager','member')), ADD CONSTRAINT ck_org_membership_status CHECK (membership_status IN ('pending','active','rejected','left','removed')), ADD CONSTRAINT ck_org_membership_approval CHECK (membership_status <> 'active' OR approved_at IS NOT NULL)");
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_memberships');
    }
};
