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
            $table->enum('type', ['repair','maintenance','upgrade','inspection','replacement','calibration'])->default('repair');
            $table->decimal('cost', 10, 2)->nullable()->change();
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('authorized_at')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('completion_date')->nullable();
            $table->boolean('completed')->default(false);
            $table->text('reason')->nullable()->change();
            $table->enum('status', ['request', 'pending', 'approved', 'denied', 'in_progress', 'completed', 'cancelled'])->default('request');
            $table->timestamps();

            $table->unsignedBigInteger('asset_key');
            $table->unsignedBigInteger('authorized_by')->nullable()->change();
            $table->unsignedBigInteger('requestor')->nullable();

            $table->foreign('asset_key')->references('id')->on('asset')->onDelete('cascade');
            $table->foreign('authorized_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('requestor')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance');
    }
};
