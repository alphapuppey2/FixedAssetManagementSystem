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
        Schema::create('activity', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->string('type');
            $table->timestamps();
            $table->unsignedBigInteger('asset_key');
            $table->unsignedBigInteger('user_key');
            $table->foreign('asset_key')->references('id')->on('asset')->onDelete('cascade');
            $table->foreign('user_key')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity');
    }
};
