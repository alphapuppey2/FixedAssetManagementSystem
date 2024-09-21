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
        Schema::create('request', function (Blueprint $table) {
            $table->id();
            $table->string("Description")->nullable();
            $table->enum('status',['pending','approved','denied'])->default('pending');
            $table->unsignedBigInteger('asset_id');
            $table->unsignedBigInteger('requestor');
            $table->unsignedBigInteger('approvedBy');
            $table->foreign('asset_id')->references('id')->on('asset')->onDelete('cascade');
            $table->foreign('requestor')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approvedBy')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request');
    }
};
