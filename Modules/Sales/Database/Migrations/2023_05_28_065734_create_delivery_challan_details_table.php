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
        Schema::create('delivery_challan_details', function (Blueprint $table) {
            $table->id();
            $table->integer('delivery_challan_id')->nullable();
            $table->integer('product_id')->nullable();
            // $table->integer('product_size_id')->nullable();
            $table->integer('product_type_id')->nullable();
            $table->integer('measuring_unit_id')->nullable();
            $table->integer('unit_price')->nullable();
            $table->integer('quantity')->nullable();
            $table->text('bundle_info')->nullable();
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
        Schema::dropIfExists('delivery_challan_details');
    }
};
