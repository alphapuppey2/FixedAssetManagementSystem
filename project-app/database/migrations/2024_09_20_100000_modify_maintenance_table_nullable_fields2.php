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
        Schema::table('maintenance', function (Blueprint $table) {

            $table->unsignedBigInteger('authorized_by')->nullable()->change(); // Make authorized_by nullable
        });
    }

    public function down()
    {
        Schema::table('maintenance', function (Blueprint $table) {

            $table->unsignedBigInteger('authorized_by')->nullable(false)->change(); // Reverse the nullable change
        });
    }

};
