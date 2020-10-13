<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSmallFarmersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('small_farmers', function (Blueprint $table) {
            $table->id();
            // $table->timestamps();

            // 대상년도
            $table->unsignedSmallInteger('business_year');
            // 지역정보(외래키)
            $table->string('sigun_code');                       // 시군코드
            $table->string('nonghyup_id');                      // 대상농협

            // 농가 인적사항
            $table->string('name');                             // 농가명(성명)
            $table->unsignedTinyInteger('age')->nullable();     // 연령(세)
            $table->enum('sex', ['M','F'])->nullable();         // 성별(Male, Female)
            $table->string('contact', 11)->nullable();          // 전화번호
            $table->string('address')->nullable();              // 도로명 주소(전체)
            $table->decimal('acreage1', 8, 1)->nullable();      // 소유경지면적(답작), 단위(ha)
            $table->decimal('acreage2', 8, 1)->nullable();      // 소유경지면적(전작), 단위(ha)
            $table->decimal('acreage3', 8, 1)->nullable();      // 소유경지면적(기타), 단위(ha)
            $table->decimal('sum_acreage', 8, 1)->nullable();   // 소유경지면적(계), 단위(ha)
            $table->text('remark')->nullable();                 // 비고
            $table->timestamps();

            // Unique index 로 중복방지
            $table->unique(['business_year', 'nonghyup_id', 'name']);

            // $table->index(['nonghyup_id', 'name']);
            // Primary key 추가 (복합 키 추가)
            // $table->primary(['id', 'parent_id']);

            // 외래키 정의
            $table->foreign('sigun_code')->references('code')->on('siguns')->onUpdate('cascade')->onDelete('cascade');  //시군 코드, softDelete인데 이 경우도 삭제가 될까? 궁금?????
            $table->foreign('nonghyup_id')->references('nonghyup_id')->on('users')->onUpdate('cascade')->onDelete('cascade'); //농협 사용자 ID
        });

        // Full Text Index
        DB::statement('ALTER TABLE small_farmers ADD FULLTEXT fulltext_index (name)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('small_farmers');
    }
}
