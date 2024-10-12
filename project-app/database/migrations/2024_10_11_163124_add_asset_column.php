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
        Schema::table('asset', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('last_used_by')->nullable();
            $table->foreign('last_used_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('asset', function (Blueprint $table) {
            $table->dropForeign(['last_used_by']); // Drop the foreign key first
            $table->dropColumn('dept_ID');    // Then drop the column
        });
    }
};
