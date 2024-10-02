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
        Schema::table('preventive', function (Blueprint $table) {
            $table->integer('occurrences')->default(0);  // Track the number of completed maintenance occurrences
            $table->enum('status', ['active', 'completed'])->default('active');  // Track if the maintenance schedule is still active
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preventive', function (Blueprint $table) {
            //
        });
    }
};
