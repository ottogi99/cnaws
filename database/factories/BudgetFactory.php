<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Budget;
use Faker\Generator as Faker;

$factory->define(Budget::class, function (Faker $faker) {
    return [
        //
        'business_year' => $faker->numberBetween(2019, 2020), //now()->format('Y'),
        'sigun_code' => function () use ($faker){
          return App\Sigun::find($faker->numberBetween(1,15))->code;
        },
        'nonghyup_id' => function () use ($faker){
          return App\User::find($faker->numberBetween(1,10))->nonghyup_id;
        },
        'amount' => $faker->numberBetween(100, 100000),
        // 'created_at' => now(),
        // 'updated_at' => now(),
    ];
});
