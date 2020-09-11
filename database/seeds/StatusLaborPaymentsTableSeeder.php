<?php

use Illuminate\Database\Seeder;

class StatusLaborPaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\StatusLaborPayment::class, 50)->create();
    }
}
