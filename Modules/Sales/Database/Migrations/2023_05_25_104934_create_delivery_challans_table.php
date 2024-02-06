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
        Schema::create('delivery_challans', function (Blueprint $table) {
            $table->id();
            $table->integer('sales_order_id')->nullable();
            $table->integer('delivery_order_id')->nullable();
            $table->string('delivery_challan_no')->nullable();
            $table->date('delivery_date')->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('delivery_address')->nullable();
            $table->string('vehicle_no')->nullable();
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
        Schema::dropIfExists('delivery_challans');
    }
};
