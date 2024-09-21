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
        Schema::table('request', function (Blueprint $table) {
            $table->unsignedBigInteger('approvedBy')->nullable()->change(); // Making 'approvedBy' nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request', function (Blueprint $table) {
            $table->unsignedBigInteger('approvedBy')->nullable(false)->change(); // Revert to non-nullable
        });
    }
};
