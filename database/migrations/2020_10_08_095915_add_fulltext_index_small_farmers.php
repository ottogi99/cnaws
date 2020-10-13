<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFulltextIndexSmallFarmers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE small_farmers ADD FULLTEXT fulltext_index (name)');
        DB::statement('ALTER TABLE large_farmers ADD FULLTEXT fulltext_index (name)');
        DB::statement('ALTER TABLE machine_supporters ADD FULLTEXT fulltext_index (name)');
        DB::statement('ALTER TABLE manpower_supporters ADD FULLTEXT fulltext_index (name)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE small_farmers DROP INDEX fulltext_index');
        DB::statement('ALTER TABLE large_farmers DROP INDEX fulltext_index');
        DB::statement('ALTER TABLE machine_supporters DROP INDEX fulltext_index');
        DB::statement('ALTER TABLE manpower_supporters DROP INDEX fulltext_index');
    }
}
