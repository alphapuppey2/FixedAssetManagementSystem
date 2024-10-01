<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusInMaintenanceTable extends Migration
{
    public function up()
    {
        Schema::table('maintenance', function (Blueprint $table) {
            // Modify the status column to include the new status options
            $table->enum('status', ['request', 'pending', 'approved', 'denied', 'in_progress', 'completed', 'cancelled'])
                  ->default('request')->change();
        });
    }

    public function down()
    {
        Schema::table('maintenance', function (Blueprint $table) {
            // Revert to the original statuses
            $table->enum('status', ['request', 'pending', 'approved', 'denied', 'cancelled'])->default('request')->change();
        });
    }
}
