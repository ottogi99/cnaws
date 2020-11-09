<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUniqueToLargeFarmersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('large_farmers', function (Blueprint $table) {
            $table->dropUnique(['business_year', 'nonghyup_id', 'name']);
            $table->unique(['business_year', 'nonghyup_id', 'name', 'birth']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('large_farmers', function (Blueprint $table) {
            $table->dropUnique(['business_year', 'nonghyup_id', 'name', 'birth']);
            $table->unique(['business_year', 'nonghyup_id', 'name']);
        });
    }
}
