<?php

use Illuminate\Database\Seeder;

class StatusOperatingCostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\StatusOperatingCost::class, 10)->create();
    }
}
