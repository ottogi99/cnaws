<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            // 사용자정의
            $table->string('sigun_code');
            $table->string('nonghyup_id');
            $table->unsignedSmallInteger('business_year');
            $table->unsignedBigInteger('amount');

            // 외래키 정의
            $table->foreign('sigun_code')->references('code')->on('siguns')->onUpdate('cascade')->onDelete('cascade');  //시군 코드
            $table->foreign('nonghyup_id')->references('nonghyup_id')->on('users')->onUpdate('cascade')->onDelete('cascade'); //농협 사용자 ID

            // Unique index 로 중복방지
            $table->unique(['nonghyup_id', 'business_year']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budgets');
    }
}
