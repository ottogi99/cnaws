<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\SmallFarmer;
use Faker\Generator as Faker;

$factory->define(SmallFarmer::class, function (Faker $faker) {
    $nonghyup_id = $faker->numberBetween(1,10);
    $acreage1 = $faker->randomFloat($nbMaxDecimals=2, $min=0, $max=1000000);
    $acreage2 = $faker->randomFloat($nbMaxDecimals=2, $min=0, $max=1000000);
    $acreage3 = $faker->randomFloat($nbMaxDecimals=2, $min=0, $max=1000000);
    $sum_acreage = $acreage1 + $acreage2 + $acreage3;

    return [
      'business_year' => $faker->numberBetween(2019, 2020),
      'sigun_code' => function () use ($nonghyup_id){
          return App\User::find($nonghyup_id)->sigun->code;
      },
      'nonghyup_id' => function () use ($nonghyup_id) {
          return App\User::find($nonghyup_id)->nonghyup_id;
      },
      'name'          => $faker->name,
      'age'           => $faker->numberBetween(0,100),
      'sex'           => $faker->randomElement($array=array('M','F')),
      'contact'       => $faker->unique()->regexify('^01(0|1|6|7|8|9)([0-9]{3,4})([0-9]{4})$'),
      'address'       => $faker->address,
      'acreage1'      => $acreage1,
      'acreage2'      => $acreage2,
      'acreage3'      => $acreage3,
      'sum_acreage'   => $sum_acreage,
      // 'created_at'    => now(),
      // 'updated_at'    => now(),
      'remark' => $faker->paragraph($nbSentences = 2, $variableNbSentences = true),
    ];
});
