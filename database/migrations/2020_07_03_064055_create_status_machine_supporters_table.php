<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusMachineSupportersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_machine_supporters', function (Blueprint $table) {
            $table->id();

            // 대상년도
            $table->unsignedSmallInteger('business_year');
            // 지역정보
            $table->string('sigun_code')->index();            // 시군명
            $table->string('nonghyup_id')->index();           // 대상농협
            // 지원농가
            // $table->string('name');                           // 농가명(성명)
            // $table->string('address')->nullable();            // 도로명 주소(전체)
            // $table->enum('sex', ['M','F'])->nullable();       // 성별(Male, Female)
            $table->unsignedBigInteger('farmer_id');          // 지원농가

            // 지원작업
            $table->unsignedBigInteger('supporter_id');       // 작업자
            $table->timestamp('job_start_date')->nullable();  // 작업 시작일
            $table->timestamp('job_end_date')->nullable();    // 작업 종료일
            $table->unsignedSmallInteger('working_days');     // 작업일수
            $table->string('work_detail');                    // 작업내용
            $table->unsignedInteger('working_area');          // 작업면적(㎡)
            // 지급액
            $table->unsignedBigInteger('payment_sum');        //합계(100%)
            $table->unsignedBigInteger('payment_do');         //도비(21%)
            $table->unsignedBigInteger('payment_sigun');      //시군비(49%)
            $table->unsignedBigInteger('payment_center');     //중앙회(20%)
            $table->unsignedBigInteger('payment_unit');       //지역농협(10%)

            $table->text('remark')->nullable();   // 비고
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);   // 삭제일

            // 키 지정
            //$table->unique('name', 'contact');                  // 키 확인 필요

            // 외래키 정의
            $table->foreign('sigun_code')->references('code')->on('siguns')->onUpdate('cascade');//->onDelete('cascade');  //시군 코드, softDelete인데 이 경우도 삭제가 될까? 궁금?????
            $table->foreign('nonghyup_id')->references('nonghyup_id')->on('users')->onUpdate('cascade');//->onDelete('cascade'); //농협 사용자 ID
            $table->foreign('supporter_id')->references('id')->on('machine_supporters')->onUpdate('cascade');//->onDelete('cascade'); //농협 사용자 ID
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_machine_supporters');
    }
}
