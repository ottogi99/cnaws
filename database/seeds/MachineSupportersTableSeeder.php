<?php

use Illuminate\Database\Seeder;

class MachineSupportersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\MachineSupporter::class, 50)->create();
    }
}
