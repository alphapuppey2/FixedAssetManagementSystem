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
        Schema::create('notifications', function (Blueprint $table) {

            $table->id();
            $table->string('title');
            $table->text('message');
            $table->enum('status', ['unread', 'read'])->default('unread');
            $table->boolean('is_deleted')->default(0);

            $table->unsignedBigInteger('authorized_by')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->foreign('authorized_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
