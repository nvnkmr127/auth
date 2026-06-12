<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_application_mapping', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auth_user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('perfex_user_id')->nullable();
            $table->unsignedBigInteger('c2d_user_id')->nullable();
            $table->unsignedBigInteger('onestudio_user_id')->nullable();
            $table->unsignedBigInteger('estimator_user_id')->nullable();
            $table->string('sync_status')->default('synced');
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_application_mapping');
    }
};
