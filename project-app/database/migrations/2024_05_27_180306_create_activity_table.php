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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('activity'); // Activity type
            $table->text('description')->nullable(); // Optional description
            $table->string('userType'); // Role of the user
            $table->unsignedBigInteger('user_id')->nullable(); // User ID
            $table->unsignedBigInteger('asset_id')->nullable(); // Asset ID
            $table->unsignedBigInteger('request_id')->nullable(); // Request ID
            $table->timestamps(); // This adds both 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
