<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivatedUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activated_users', function (Blueprint $table) {
            $table->id();

            $table->unsignedSmallInteger('business_year');
            $table->string('nonghyup_id');

            $table->timestamps();

            // Unique index 로 중복방지
            $table->unique(['business_year', 'nonghyup_id']);

            // 외래키 정의
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
        Schema::dropIfExists('activated_users');
    }
}
