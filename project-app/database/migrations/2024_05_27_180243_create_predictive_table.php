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
        Schema::create('predictive', function (Blueprint $table) {
            $table->id();
            $table->integer('repair_count');
            $table->float('average_cost');
            $table->enum('recommendation',['maintenance','repair','dispose'])->default('maintenance');
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
        Schema::dropIfExists('predictive');
    }
};
