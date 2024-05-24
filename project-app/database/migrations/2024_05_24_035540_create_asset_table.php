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
        Schema::create('asset', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('ctg_ID');
            $table->unsignedBigInteger('dept_ID');
            $table->foreign('ctg_ID')->references('id')->on('category')->onDelete('cascade');
            $table->foreign('dept_ID')->references('id')->on('department')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset');
    }
};
