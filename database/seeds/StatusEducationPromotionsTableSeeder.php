<?php

use Illuminate\Database\Seeder;

class StatusEducationPromotionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\StatusEducationPromotion::class, 50)->create();
    }
}
