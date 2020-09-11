<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusLaborPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_labor_payments', function (Blueprint $table) {
            $table->id();

            // 대상년도
            $table->unsignedSmallInteger('business_year');
            // 지역정보
            $table->string('sigun_code')->index();        // 시군명
            $table->string('nonghyup_id')->index();             // 대상농협
            // 전담인력
            $table->string('name');                       // 성명
            $table->string('birth');                      // 생년월일
            // $table->string('contact');                    // 연락처
            $table->string('bank_name')->nullable();      // 입금정보(은행명)
            $table->string('bank_account')->nullable();   // 입금정보(계좌번호)

            // 지급액
            $table->string('detail')->nullable();   // 지출내역
            $table->timestamp('payment_date')->nullable();  // 지급일자
            $table->unsignedBigInteger('payment_sum');      //합계(100%) (위 지급항목1/2/3의 합)
            $table->unsignedBigInteger('payment_do');       //도비(21%)
            $table->unsignedBigInteger('payment_sigun');    //시군비(49%)
            $table->unsignedBigInteger('payment_center');   //중앙회(20%)
            $table->unsignedBigInteger('payment_unit');     //지역농협(10%)

            // 근무실적
            // $table->timestamp('job_start_date')->nullable();  // 근무 시작일
            // $table->timestamp('job_end_date')->nullable();    // 근무 종료일
            // $table->unsignedSmallInteger('working_days');     // 근무일수

            $table->text('remark')->nullable();   // 비고
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);   // 삭제일

            // 키 지정
            //$table->unique('name', 'contact');                  // 키 확인 필요

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
        Schema::dropIfExists('status_labor_payments');
    }
}
