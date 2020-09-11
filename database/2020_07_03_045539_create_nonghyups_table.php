<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNonghyupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nonghyups', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('sigun_code')->index();
            $table->string('nh_id')->index();

            // 외래키 정의
            $table->foreign('sigun_code')->references('code')->on('siguns')->onUpdate('cascade')->onDelete('cascade');  //시군 코드
            $table->foreign('nh_id')->references('user_id')->on('users')->onUpdate('cascade')->onDelete('cascade'); //농협 사용자 ID

            // 사용자 정의부
            $table->string('name',255)->unique();
            $table->string('address',255);
            $table->string('contact',11);
            $table->string('representative',255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nonghyups');
    }
}
