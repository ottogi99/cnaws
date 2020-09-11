<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Nonghyup;
use Faker\Generator as Faker;

$factory->define(Nonghyup::class, function (Faker $faker) {
    $date = $faker->dateTimeThisMonth;

    return [
        'nh_id' => $faker->unique()->regexify('^[A-Za-z]{1}[A-Za-z0-9_]{4,19}$'),
        'name' => $faker->name,
        // 'password' => $faker->password,
        'address' => $faker->address,
        'contact' => $faker->unique()->regexify('^01(0|1|6|7|8|9)([0-9]{3,4})([0-9]{4})$'),
        'representative' => $faker->name,
        'created_at' => $date,
        'updated_at' => $date,
        'sigun_code' => function () {
          // return factory(App\Sigun::class)->create()->id;
          // return App\Sigun::find($faker->numberBetween($min=1, $max=15))->code;
          return App\Sigun::find(1)->code;
        },
    ];
});
