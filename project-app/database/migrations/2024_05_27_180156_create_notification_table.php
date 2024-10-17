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
        Schema::create('notification', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type');
            $table->string('worker_name');
            $table->enum('status' , ['unread','read']);
            $table->timestamps();
            $table->unsignedBigInteger('asset_ID');
            $table->foreign('asset_ID')->references('id')->on('asset')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification');
    }
};
