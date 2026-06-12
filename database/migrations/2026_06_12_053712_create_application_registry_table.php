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
        Schema::create('application_registry', function (Blueprint $table) {
            $table->id();
            $table->string('app_name');
            $table->string('app_slug')->unique();
            $table->string('app_icon')->nullable();
            $table->string('app_type')->default('web'); // e.g., web, native, hybrid
            $table->string('app_version')->nullable();
            $table->boolean('mobile_enabled')->default(true);
            $table->string('status')->default('active');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_registry');
    }
};
