<?php

use Illuminate\Database\Seeder;

class StatusManpowerSupportersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\StatusManpowerSupporter::class, 50)->create();
    }
}
