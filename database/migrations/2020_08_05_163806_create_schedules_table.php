<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();

            $table->boolean('is_allow')->default(0);            // 자료입력 허용여부
            $table->boolean('is_period')->default(0);           // 자료입력 기간 적용여부
            $table->timestamp('input_start_date')->nullable();  // 자료입력 시작일
            $table->timestamp('input_end_date')->nullable();    // 자료입력 종료일

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
        Schema::dropIfExists('schedules');
    }
}
