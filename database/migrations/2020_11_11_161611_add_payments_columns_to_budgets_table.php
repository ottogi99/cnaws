<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentsColumnsToBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_do')->default(0);       //도비(21%)
            $table->unsignedBigInteger('payment_sigun')->default(0);    //시군비(49%)
            $table->unsignedBigInteger('payment_center')->default(0);   //중앙회(20%)
            $table->unsignedBigInteger('payment_unit')->default(0);     //지역농협(10%)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropColumn([
              'payment_do',
              'payment_sigun',
              'payment_center',
              'payment_unit'
            ]);
        });
    }
}
