<?php

use Illuminate\Database\Seeder;

class SchedulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Schedule::create([
          'is_allow' => 0,
          'is_period' => 0
        ]);
    }
}
