<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // The actor
            $table->string('action'); // e.g., 'role.created', 'access.granted'
            $table->string('module'); // e.g., 'Roles', 'AppAccess'
            $table->unsignedBigInteger('target_id')->nullable(); // ID of the affected resource
            $table->string('target_type')->nullable(); // Class name of the affected resource
            $table->json('changes')->nullable(); // Old vs New values
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
