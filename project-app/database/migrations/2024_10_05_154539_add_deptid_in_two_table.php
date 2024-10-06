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
        Schema::table('manufacturer', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('dept_ID')->nullable();
            $table->foreign('dept_ID')->references('id')->on('department')->onDelete('cascade');
        });

        Schema::table('model', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('dept_ID')->nullable();
            $table->foreign('dept_ID')->references('id')->on('department')->onDelete('cascade');
        });
        Schema::table('location', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('dept_ID')->nullable();
            $table->foreign('dept_ID')->references('id')->on('department')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacturer');
        Schema::dropIfExists('model');
        Schema::dropIfExists('location');

    }
};
