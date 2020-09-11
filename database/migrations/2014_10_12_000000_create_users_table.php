<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            // 사용자 정의부
            $table->string('nonghyup_id')->unique();              // 농협ID
            $table->string('password');                           // 접속 비밀번호
            $table->string('sigun_code')->index();                // 담당 시군코드
            $table->string('name');                               // 사용자(농협)명
            $table->string('address')->nullable();                // 사용자(농협)주소
            $table->string('contact',11)->nullable();             // 사용자(농협)연락처(담당자)
            $table->string('representative')->nullable();         // 사용자(농협)대표자
            $table->boolean('activated')->default(0);             // 사용자(농협)활성화여부(0,1)
            $table->boolean('is_admin')->default(0);              // 사용자(농협)관리자여부(0,1)
            $table->unsignedTinyInteger('sequence')->default(0);
            //기존 내용
            // $table->string('email')->nullable();
            // $table->string('email')->unique();
            // $table->timestamp('email_verified_at')->nullable();
            // $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // 외래키 정의
            $table->foreign('sigun_code')->references('code')->on('siguns')->onUpdate('cascade')->onDelete('cascade');  //시군 코드
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
