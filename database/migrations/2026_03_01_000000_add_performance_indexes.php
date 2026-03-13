<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Add indexes to frequently queried columns for performance optimization.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('email');
            $table->index('created_at');
        });

        Schema::table('user_app_accesses', function (Blueprint $table) {
            $table->index('app_id');
            $table->index('user_id');
            $table->index('status');
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('module');
            $table->index('action');
            $table->index('created_at');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->index('app_id');
            $table->index('is_global');
        });

        Schema::table('user_roles', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('role_id');
        });

        Schema::table('role_permissions', function (Blueprint $table) {
            $table->index('role_id');
            $table->index('permission_id');
        });

        Schema::table('api_tokens', function (Blueprint $table) {
            $table->index('expires_at');
        });

        Schema::table('user_otps', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('expires_at');
            $table->index('used');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['email']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('user_app_accesses', function (Blueprint $table) {
            $table->dropIndex(['app_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['module']);
            $table->dropIndex(['action']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropIndex(['app_id']);
            $table->dropIndex(['is_global']);
        });

        Schema::table('user_roles', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['role_id']);
        });

        Schema::table('role_permissions', function (Blueprint $table) {
            $table->dropIndex(['role_id']);
            $table->dropIndex(['permission_id']);
        });

        Schema::table('api_tokens', function (Blueprint $table) {
            $table->dropIndex(['expires_at']);
        });

        Schema::table('user_otps', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['expires_at']);
            $table->dropIndex(['used']);
        });
    }
};
