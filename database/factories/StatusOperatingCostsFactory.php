<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\StatusOperatingCost;
use Faker\Generator as Faker;

$factory->define(StatusOperatingCost::class, function (Faker $faker) {
    $nonghyup_id = $faker->numberBetween(1,10);
    $sum = $faker->numberBetween($min=0, $max=10000000);

    return [
        'business_year' => $faker->numberBetween(2019,2020),
        'sigun_code' => function () use ($nonghyup_id){
            return App\User::find($nonghyup_id)->sigun->code;
        },
        'nonghyup_id' => function () use ($nonghyup_id) {
            return App\User::find($nonghyup_id)->nonghyup_id;
        },
        'item' => $faker->word,
        'target' => $faker->word,
        'detail' => $faker->sentence($nbWords = 2, $variableNbWords = true),
        'payment_date' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = 'Asia/Seoul')->format('Y-m-d'),
        'payment_sum' => $sum,
        'payment_do' => $sum * 0.21,
        'payment_sigun' => $sum * 0.49,
        'payment_center' => $sum * 0.2,
        'payment_unit' => $sum * 0.1,
        'remark' => '',
    ];
});
