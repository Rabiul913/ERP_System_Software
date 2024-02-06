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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string("name", 100);
            $table->string("address", 200);
            $table->string("business_type", 100);
            $table->string("bin_no", 100);
            $table->string("tin_no", 100);
            $table->string("trade_license_no", 100);
            $table->string("limit", 100);
            $table->string("country", 100);
            $table->string("contact_person_name", 100);
            $table->string("nid_no", 100);
            $table->string("contact_no", 100);
            $table->string("email", 100);
            $table->integer("sales_person_id");
            $table->string("sales_person_number", 100);
            $table->bigInteger("zone_id");
            $table->string("status", 100);
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
        Schema::dropIfExists('customers');
    }
};
