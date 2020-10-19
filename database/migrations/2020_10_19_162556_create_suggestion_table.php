<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuggestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::create('suggestion', function (Blueprint $table) {
             $table->id();
             $table->unsignedBigInteger('user_id')->index();
             $table->string('title', 255);
             $table->text('content');
             $table->unsignedInteger('hit')->default(0);
             $table->unsignedInteger('disclose')->default(0);     // 0: 공개, 1:비공개
             $table->timestamps();

             // 외래키 정의
             $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade'); //농협 사용자 ID
         });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
         Schema::dropIfExists('suggestion');
     }
}
