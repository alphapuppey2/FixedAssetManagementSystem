<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('asset', function (Blueprint $table) {
            $table->boolean('isDeleted')->default(0); // This adds the deleted_at column
        });
    }

    public function down()
    {
        Schema::table('asset', function (Blueprint $table) {
            $table->dropColumn('isDeleted'); // Drop column on rollback
        });
    }
};
