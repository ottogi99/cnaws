<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBirthToSmallFarmersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('small_farmers', function (Blueprint $table) {
            $table->date('birth')->default('0000-01-01');
            // $table->dropUnique(['business_year', 'nonghyup_id', 'name']);
            // $table->unique(['business_year', 'nonghyup_id', 'name', 'birth']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('small_farmers', function (Blueprint $table) {
            $table->dropColumn(['birth']);
            // $table->dropUnique(['business_year', 'nonghyup_id', 'name', 'birth']);
            // $table->unique(['business_year', 'nonghyup_id', 'name']);
        });
    }
}
