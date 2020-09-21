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
            // $table->foreign('worker_id')->references('nonghyup_id')->on('users')->onUpdate('cascade');
            // $table->foreign('target_id')->references('nonghyup_id')->on('users')->onUpdate('cascade'); //농협 사용자 ID
        });
    }
user_histories_target_id_foreign
user_histories_worker_id_foreign

user_histories_target_id_foreign
user_histories_worker_id_foreign

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
