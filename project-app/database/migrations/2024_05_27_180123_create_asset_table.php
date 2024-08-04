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
            $table->blob('image')->nullable();
            $table->enum('status', ['active','deployed','need Repair','under Maintenance','dispose'])->default('active');
            $table->unsignedBigInteger('ctg_ID');
            $table->unsignedBigInteger('dept_ID');
            $table->unsignedBigInteger('manufacturer_key');
            $table->unsignedBigInteger('maintenance_key');
            $table->unsignedBigInteger('model_key');
            $table->binary('custom_fields');
            $table->foreign('model_key')->references('id')->on('model')->onDelete('cascade');
            $table->foreign('manufacturer_key')->references('id')->on('manufacturer')->onDelete('cascade');
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
