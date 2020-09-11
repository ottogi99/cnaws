<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusOperatingCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_operating_costs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // 대상년도
            $table->unsignedSmallInteger('business_year');
            // 지역정보
            $table->string('sigun_code')->index();        // 시군명
            $table->string('nonghyup_id')->index();             // 대상농협
            // 지출현황
            $table->string('item');                        // 지출항목
            $table->string('target');                       // 지급대상
            $table->string('detail');                     // 내용
            // 지급액
            $table->timestamp('payment_date')->nullable();  // 지급일자
            $table->unsignedBigInteger('payment_sum');      //합계(100%)
            $table->unsignedBigInteger('payment_do');       //도비(21%)
            $table->unsignedBigInteger('payment_sigun');    //시군비(49%)
            $table->unsignedBigInteger('payment_center');   //중앙회(20%)
            $table->unsignedBigInteger('payment_unit');     //지역농협(10%)

            $table->string('remark')->nullable();   // 비고
            $table->softDeletes('deleted_at', 0);   // 삭제일

            // 외래키 정의
            $table->foreign('sigun_code')->references('code')->on('siguns')->onUpdate('cascade')->onDelete('cascade');  //시군 코드, softDelete인데 이 경우도 삭제가 될까? 궁금?????
            $table->foreign('nonghyup_id')->references('nonghyup_id')->on('users')->onUpdate('cascade')->onDelete('cascade'); //농협 사용자 ID
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_operating_costs');
    }
}
