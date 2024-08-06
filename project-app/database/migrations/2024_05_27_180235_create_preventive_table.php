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

        Schema::create('preventive', function (Blueprint $table) {
            $table->id();
            $table->float('cost');
            $table->integer('frequency');
            $table->integer('ends');
            $table->timestamps();
            $table->unsignedBigInteger('asset_key');
            $table->foreign('asset_key')->references('id')->on('asset')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preventive');
    }
};
