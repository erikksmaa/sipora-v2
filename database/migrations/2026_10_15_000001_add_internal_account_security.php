<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->boolean('internal_active')->default(true);
            $table->boolean('must_change_password')->default(false);
            $table->unsignedBigInteger('internal_session_version')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn(['internal_active', 'must_change_password', 'internal_session_version']));
    }
};
