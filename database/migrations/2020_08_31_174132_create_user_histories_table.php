<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_histories', function (Blueprint $table) {
            $table->id();
            $table->string('worker_id');
            $table->string('target_id');
            $table->string('contents');
            $table->timestamps();

            // 외래키 정의
            $table->foreign('worker_id')->references('nonghyup_id')->on('users');  //시군 코드, softDelete인데 이 경우도 삭제가 될까? 궁금?????
            $table->foreign('target_id')->references('nonghyup_id')->on('users'); //농협 사용자 ID
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_histories');
    }
}
