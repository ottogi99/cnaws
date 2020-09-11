<?php

use Illuminate\Database\Seeder;

class ManpowerSupportersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\ManpowerSupporter::class, 50)->create();
    }
}
