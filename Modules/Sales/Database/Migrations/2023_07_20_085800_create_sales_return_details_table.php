<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_return_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sales_return_id')->nullable();
            // $table->bigInteger('product_type_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            // $table->bigInteger('product_size_id')->nullable();
            $table->bigInteger('measuring_unit_id')->nullable();
            $table->string('return_price')->nullable();
            $table->string('quantity')->nullable();
            $table->uuid('com_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_return_details');
    }
};
