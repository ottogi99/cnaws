<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notice_id')->nullable()->index();
            $table->unsignedBigInteger('manual_id')->nullable()->index();
            $table->unsignedBigInteger('suggestion_id')->nullable()->index();
            $table->string('stored_name');
            $table->string('original_name');
            $table->unsignedInteger('bytes')->nullable();
            $table->string('mime')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachment');
    }
}
