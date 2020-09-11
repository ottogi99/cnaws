<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLargeFarmersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('large_farmers', function (Blueprint $table) {
            $table->id();

            // 대상년도
            $table->unsignedSmallInteger('business_year');
            // 지역정보
            $table->string('sigun_code');        // 시군명
            $table->string('nonghyup_id');             // 대상농협
            // 인적사항
            $table->string('name');                         // 농가명(성명)
            $table->unsignedTinyInteger('age')->nullabel(); // 연령(세)
            $table->enum('sex', ['M','F'])->nullable();     // 성별
            $table->string('address')->nullable();          // 도로명 주소(전체)
            $table->string('contact', 11);                  // 연락처
            $table->decimal('acreage', 8, 2)->nullable();   // 소유경지면적, 단위(ha)
            $table->string('cultivar')->nullable();         // 재배품목(품종)
            $table->string('bank_name')->nullable();        // 입금정보(은행명)
            $table->string('bank_account')->nullable();     // 입금정보(계좌번호)
            $table->text('remark')->nullable();             // 비고
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);           // 삭제일

            // 키 지정
            $table->unique(['business_year', 'nonghyup_id', 'name']);                  // 이름과 연락처로 중복 방지

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
        Schema::dropIfExists('large_farmers');
    }
}
