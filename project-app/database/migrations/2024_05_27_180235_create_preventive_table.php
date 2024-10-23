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
            $table->integer('occurrences')->default(0);  // Track the number of completed maintenance occurrences
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');  // Track if the maintenance schedule is still active
            $table->text('cancel_reason')->nullable();
            // $table->timestamp('next_maintenance_timestamp')->nullable(); // Timestamp for next maintenance
            // $table->datetime('next_maintenance_timestamp')->nullable();
            $table->bigInteger('next_maintenance_timestamp')->nullable();
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
