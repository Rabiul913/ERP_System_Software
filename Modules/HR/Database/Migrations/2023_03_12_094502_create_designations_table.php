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
        Schema::create('designations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('bangla')->nullable();
            $table->string('attendance_bonus')->nullable();
            $table->string('holiday_bonus')->nullable();
            $table->string('night_shift_allowance')->nullable();
            $table->string('grade_id')->nullable();
            $table->string('SI_no')->nullable();
            $table->string('status')->default('active'); // active, inactive
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
        Schema::dropIfExists('designations');
    }
};
