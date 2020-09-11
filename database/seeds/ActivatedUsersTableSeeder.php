<?php

use Illuminate\Database\Seeder;

class ActivatedUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nonghyups = \App\User::with('sigun')->get();

        foreach ($nonghyups as $nonghyup) {
            \App\ActivatedUser::create([
              'business_year' => now()->subYear()->format('Y'),
              'nonghyup_id' => $nonghyup->nonghyup_id,
            ]);
        }
    }
}
