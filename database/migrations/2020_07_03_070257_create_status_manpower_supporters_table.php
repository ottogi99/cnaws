<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusManpowerSupportersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_manpower_supporters', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // 대상년도
            $table->unsignedSmallInteger('business_year');
            // 지역정보
            $table->string('sigun_code')->index();          // 시군명
            $table->string('nonghyup_id')->index();               // 대상농협
            // 지원농가
            // $table->string('name');                         // 농가명(성명)
            // $table->string('address')->nullable();          // 도로명 주소(전체)
            // $table->enum('sex', ['M','F'])->nullable();     // 성별(Male, Female)
            $table->unsignedBigInteger('farmer_id');          // 지원농가

            // 지원작업
            // $table->string('supporter');                      // 작업자
            $table->unsignedBigInteger('supporter_id');          // 작업자
            $table->timestamp('job_start_date')->nullable();  // 작업 시작일
            $table->timestamp('job_end_date')->nullable();    // 작업 종료일
            $table->unsignedSmallInteger('working_days');     // 작업일수
            $table->string('work_detail');                  // 작업내용
            // 지급내액
            $table->enum('recipient', ['S','F']);           // 지급수령자(제공 받은사람)
            $table->unsignedInteger('payment_item1')->nullable();       //지급항목1(교통비)
            $table->unsignedInteger('payment_item2')->nullable();       //지급항목2(간식비)
            $table->unsignedInteger('payment_item3')->nullable();       //지급항목3(마스크구입비)
            // 지급액
            $table->unsignedBigInteger('payment_sum');      //합계(100%) (위 지급항목1/2/3의 합)
            $table->unsignedBigInteger('payment_do');       //도비(21%)
            $table->unsignedBigInteger('payment_sigun');    //시군비(49%)
            $table->unsignedBigInteger('payment_center');   //중앙회(20%)
            $table->unsignedBigInteger('payment_unit');     //지역농협(10%)

            $table->string('remark')->nullable();   // 비고

            // 2020-12-07 중복데이터가 들어가니깐(중복 Request) unique를 통한 중복 방지
            $table->unique(['business_year', 'farmer_id', 'job_start_date', 'job_end_date']); // 동작업자가 동일일에 여러 농가에 중복되지 않도록 (기간인 경우 중복될지도)

            // 외래키 정의
            $table->foreign('sigun_code')->references('code')->on('siguns')->onUpdate('cascade')->onDelete('cascade');  //시군 코드, softDelete인데 이 경우도 삭제가 될까? 궁금?????
            $table->foreign('nonghyup_id')->references('nonghyup_id')->on('users')->onUpdate('cascade')->onDelete('cascade'); //농협 사용자 ID
            $table->foreign('farmer_id')->references('id')->on('large_farmers')->onUpdate('cascade')->onDelete('cascade'); //농가 ID
            $table->foreign('supporter_id')->references('id')->on('manpower_supporters')->onUpdate('cascade')->onDelete('cascade'); //지원반 ID
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_manpower_supporters');
    }
}
