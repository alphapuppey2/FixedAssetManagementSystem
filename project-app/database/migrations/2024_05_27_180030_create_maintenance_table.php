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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance');
    }
};
