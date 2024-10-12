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
        //
        Schema::create('userAssetActivity', function (Blueprint $table) {
            $table->id();

            $table->timestamp('date_acquired')->nullable();
            $table->timestamp('date_returned')->nullable();

            $table->unsignedBigInteger('used_by')->nullable();
            $table->unsignedBigInteger('asset_id')->nullable();
            $table->unsignedBigInteger('assigned_by')->nullable();


            $table->foreign('asset_id')->references('id')->on('asset')->onDelete('cascade');
            $table->foreign('used_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_by')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('userAssetActivity');
    }
};
