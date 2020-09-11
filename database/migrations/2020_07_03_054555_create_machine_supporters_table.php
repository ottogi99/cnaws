<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMachineSupportersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machine_supporters', function (Blueprint $table) {
            $table->id();

            // 대상년도
            $table->unsignedSmallInteger('business_year');
            // 지역정보
            $table->string('sigun_code');                 // 시군명
            $table->string('nonghyup_id');                // 대상농협
            // 인적사항
            $table->string('name');                       // 지원자 성명
            $table->unsignedTinyInteger('age');           // 지원자 연령(세)
            $table->enum('sex', ['M','F'])->nullable();   // 성별(남, 여)
            $table->string('contact', 11)->nullable();    // 전화번호
            $table->string('address')->nullable();        // 도로명 주소(전체)
            $table->string('machine1')->nullable();       // 소유 농기계1(기종)
            $table->string('machine2')->nullable();       // 소유 농기계2(기종)
            $table->string('machine3')->nullable();       // 소유 농기계3(기종)
            $table->string('machine4')->nullable();       // 소유 농기계4(기종)
            $table->string('bank_name')->nullable();      // 입금정보(은행명)
            $table->string('bank_account')->nullable();   // 입금정보(계좌번호)
            $table->text('remark')->nullable();         // 비고
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);         // 삭제일

            // 키 지정
            $table->unique(['business_year, nonghyup_id', 'name']);      // 하나의 농협이 동일한 이름의 지원단 등록 제한

            // 외래키 정의
            $table->foreign('sigun_code')->references('code')->on('siguns')->onUpdate('cascade')->onDelete('cascade');          //시군 코드, softDelete인데 이 경우도 삭제가 될까? 궁금?????
            $table->foreign('nonghyup_id')->references('nonghyup_id')->on('users')->onUpdate('cascade')->onDelete('cascade');   //농협 사용자 ID
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('machine_supporters');
    }
}
