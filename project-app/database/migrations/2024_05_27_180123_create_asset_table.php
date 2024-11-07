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
            $table->string('asst_img')->nullable();
            $table->string('qr_img')->nullable();
            $table->string('code')->unique();
            $table->timestamp('purchase_date')->useCurrent();
            $table->integer('usage_lifespan')->nullable();
            $table->integer('salvage_value')->nullable();
            $table->integer('purchase_cost')->nullable();
            $table->integer('depreciation')->nullable();
            $table->enum('status', ['active', 'deployed', 'under_maintenance', 'disposed'])->default('active');

            $table->text('custom_fields')->nullable();

            $table->unsignedBigInteger('ctg_ID');
            $table->unsignedBigInteger('dept_ID');
            $table->unsignedBigInteger('manufacturer_key');
            $table->unsignedBigInteger('model_key');
            $table->unsignedBigInteger('loc_key');
            $table->unsignedBigInteger('last_used_by')->nullable();

            $table->foreign('last_used_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('model_key')->references('id')->on('model')->onDelete('cascade');
            $table->foreign('loc_key')->references('id')->on('location')->onDelete('cascade');
            $table->foreign('manufacturer_key')->references('id')->on('manufacturer')->onDelete('cascade');
            $table->foreign('ctg_ID')->references('id')->on('category')->onDelete('cascade');
            $table->foreign('dept_ID')->references('id')->on('department')->onDelete('cascade');
            $table->timestamps();
            $table->boolean('isDeleted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Disable foreign key checks
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Drop the table
        Schema::dropIfExists('asset');

        // Enable foreign key checks
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
