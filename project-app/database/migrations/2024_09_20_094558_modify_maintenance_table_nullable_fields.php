<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('maintenance', function (Blueprint $table) {
            $table->decimal('cost', 10, 2)->nullable()->change();
            $table->text('reason')->nullable()->change();
            $table->unsignedBigInteger('authorized_by')->nullable()->change(); // Make authorized_by nullable
        });
    }

    public function down()
    {
        Schema::table('maintenance', function (Blueprint $table) {
            $table->decimal('cost', 10, 2)->nullable(false)->change();
            $table->text('reason')->nullable(false)->change();
            $table->unsignedBigInteger('authorized_by')->nullable(false)->change(); // Reverse the nullable change
        });
    }


};
