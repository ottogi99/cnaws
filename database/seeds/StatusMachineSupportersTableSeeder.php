<?php

use Illuminate\Database\Seeder;

class StatusMachineSupportersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\StatusMachineSupporter::class, 50)->create();
    }
}
