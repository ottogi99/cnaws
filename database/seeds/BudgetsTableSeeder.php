<?php

use Illuminate\Database\Seeder;
// use Faker\Generator as Faker;

class BudgetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $nonghyups = \App\User::with('sigun')->get();

        foreach ($nonghyups as $nonghyup) {
            App\Budget::create([
              'sigun_code' => $nonghyup->sigun_code,
              'nonghyup_id' => $nonghyup->nonghyup_id,
              'business_year' => now()->format('Y'),
              'amount' => $faker->numberBetween(100, 100000),
            ]);

            App\Budget::create([
              'sigun_code' => $nonghyup->sigun_code,
              'nonghyup_id' => $nonghyup->nonghyup_id,
              'business_year' => date("Y")-1,
              'amount' => $faker->numberBetween(100, 100000),
            ]);
        }

        // factory(App\Budget::class, 10)->create();
    }
}
