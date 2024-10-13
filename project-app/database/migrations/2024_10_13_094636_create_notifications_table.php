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
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Links to the users table
            $table->string('title'); // Title of the notification
            $table->text('message'); // Detailed message
            $table->enum('status', ['unread', 'read'])->default('unread'); // Read status
            $table->boolean('is_deleted')->default(0); // 0 = not deleted, 1 = deleted
            $table->timestamps(); // created_at & updated_at timestamps
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
