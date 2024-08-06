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
        Schema::create('maintenance', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->string('type');
            $table->float('cost');
            $table->string('requested_at');
            $table->string('authorized_at');
            $table->timestamp('completion')->nullable();
            $table->unsignedBigInteger('asset_key');
            $table->unsignedBigInteger('authorized_by');
            $table->unsignedBigInteger('requestor');
            $table->foreign('authorized_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('requestor')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('predictive', function (Blueprint $table) {
            $table->id();
            $table->integer('repair_count');
            $table->float('average_cost');
            $table->enum('recommendation',['maintenance','repair','dispose'])->default('maintenance');
            $table->timestamps();
            $table->unsignedBigInteger('asset_key');
            $table->foreign('asset_key')->references('id')->on('assets')->onDelete('cascade');

        });
        Schema::create('preventive', function (Blueprint $table) {
            $table->id();
            $table->float('cost');
            $table->integer('frequency');
            $table->integer('ends');
            $table->timestamps();
            $table->unsignedBigInteger('asset_key');
            $table->foreign('asset_key')->references('id')->on('assets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance');
        Schema::dropIfExists('preventive');
        Schema::dropIfExists('predictive');
    }
};
