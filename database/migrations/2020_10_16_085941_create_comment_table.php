<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            // $table->unsignedBigInteger('parent_id')->nullable();    // 댓글의 댓글을 위함
            // $table->string('commentable_type');                     // 모델 이름
            // $table->integer('commentable_id')->unsigned();          // 모델의 기본키(건의사항 키)
            $table->unsignedBigInteger('suggestion_id')->unsigned();          // 모델의 기본키(건의사항 키)
            $table->text('content');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('suggestion_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('parent_id')->references('id')->on('comments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comment', function (Blueprint $table) {
            // $table->dropForeign('comments_parent_id_foreign');
            $table->dropForeign('comment_user_id_foreign');
            $table->dropForeign('comment_suggestion_id_foreign');
        });

        Schema::dropIfExists('comment');
    }
}
