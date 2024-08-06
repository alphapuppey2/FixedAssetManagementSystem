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
        Schema::create("category", function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("name");
            //foriegn key
            $table->unsignedBigInteger("department")->nullable();
            $table->foreign("department")->references("id")->on("department")->onDelete("cascade");
        });


        Schema::create("assets", function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("assetCode")->unique();
            $table->binary("image")->nullable();
            $table->string("name");
            $table->string("description")->nullable();
            $table->string("serial_number")->nullable();
            $table->string("manufacturer");
            $table->string("model");
            $table->string("location");
            $table->decimal("cost",10,2)->default(0);
            $table->decimal("depreciation",10,2)->default(0.00);
            $table->decimal("salvageVal",10,2)->default(0.00);
            $table->date("year");
            $table->enum("status" , ['deployed','underM','storage','disposed'])->default('deployed');
            $table->timestamps();
            //foriegn Keys this is for Additional Information of a asset
            $table->unsignedBigInteger("userID")->nullable();
            $table->foreign("userID")->references("id")->on("users")->onDelete("cascade");



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('category');
        Schema::dropIfExists('assets');
    }
};





      // Schema::create("itAsset", function (Blueprint $table) {
        //     $table->bigIncrements("id");
        //     $table->string("name");

        // });
        // Schema::create("productionAsset", function (Blueprint $table) {
        //     $table->bigIncrements("id");
        //     $table->string("name");

        // });
        // Schema::create("procurementAsset", function (Blueprint $table) {
        //     $table->bigIncrements("id");
        //     $table->string("name");

        // });
        // Schema::create("fleetAsset", function (Blueprint $table) {
        //     $table->bigIncrements("id");
        //     $table->string("name");
        // });
        //

         // $table->unsignedBigInteger("itID")->nullable();
            // $table->foreign("itID")->references("id")->on("itAsset")->onDelete("cascade");

            // $table->unsignedBigInteger("categoryID")->nullable();
            // $table->unsignedBigInteger("productID")->nullable();
            // $table->unsignedBigInteger("procureID")->nullable();
            // $table->unsignedBigInteger("fleetID")->nullable();
            // $table->foreign("categoryID")->references("id")->on("category")->onDelete("cascade");
            // $table->foreign("productID")->references("id")->on("productAsset")->onDelete("cascade");
            // $table->foreign("procureID")->references("id")->on("procurementAsset")->onDelete("cascade");
            // $table->foreign("fleetID")->references("id")->on("fleetAsset")->onDelete("cascade");
