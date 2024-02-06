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
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('address')->nullable();
            $table->integer('employee_id')->nullable();
            $table->integer('zone_id')->nullable();
            $table->string('delivery_address')->nullable();
            $table->date('date')->nullable();
            $table->integer('vat')->nullable();
            $table->integer('tax')->nullable();
            $table->integer('rent')->nullable();
            $table->integer('labor_cost')->nullable();
            $table->integer('discount')->nullable();
            $table->integer('total')->nullable();
            $table->integer('paid')->nullable();
            $table->integer('due')->nullable();
            $table->string('delivery_method')->nullable();
            $table->integer('status')->default(0);
            $table->integer('completed_by')->nullable();
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
        Schema::dropIfExists('delivery_orders');
    }
};
