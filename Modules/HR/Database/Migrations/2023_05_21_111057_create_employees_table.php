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
        Schema::create('employees', function (Blueprint $table) {

            $table->id();
            $table->string('emp_code');
            $table->string('emp_name');
            $table->string('emp_name_bangla');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('sub_section_id')->nullable();
            $table->unsignedBigInteger('designation_id');
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('floor_id')->nullable();
            $table->unsignedBigInteger('line_id')->nullable();
            $table->date('birth_date');
            $table->date('join_date');
            $table->date('increment_date')->nullable();
            $table->date('promotion_date')->nullable();
            $table->date('confirm_date')->nullable();
            $table->unsignedBigInteger('employee_type_id');
            $table->unsignedBigInteger('shift_id');
            $table->unsignedBigInteger('gender_id');
            $table->unsignedBigInteger('religion_id');
            $table->string('bllod_group')->nullable();
            $table->longText('finger_id')->nullable();
            $table->string('nid_no');
            $table->string('phone_1');
            $table->string('phone_2')->nullable();
            $table->string('email')->nullable();
            $table->string('skill')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_ot_allow')->default(0);
            $table->tinyInteger('is_bonus')->default(0);
            $table->tinyInteger('is_holiday_allow')->default(0);
            $table->tinyInteger('is_night_allow')->default(0);

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
        Schema::dropIfExists('employees');
    }
};
