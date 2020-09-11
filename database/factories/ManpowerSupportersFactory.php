<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ManpowerSupporter;
use Faker\Generator as Faker;

$factory->define(ManpowerSupporter::class, function (Faker $faker) {
    $nonghyup_id = $faker->numberBetween(1,10);

    return [
      'business_year' => $faker->numberBetween(2019, 2020),
      'sigun_code' => function () use ($nonghyup_id){
          return App\User::find($nonghyup_id)->sigun->code;
      },
      'nonghyup_id' => function () use ($nonghyup_id) {
          return App\User::find($nonghyup_id)->nonghyup_id;
      },
      'name' => $faker->name,
      'age' => $faker->numberBetween(0,150),
      'sex' => $faker->randomElement($array=array('M','F')),
      'contact' => $faker->unique()->regexify('^01(0|1|6|7|8|9)([0-9]{3,4})([0-9]{4})$'),
      'address' => $faker->address,
      'training_date1' => $faker->company,
      'training_date2' => $faker->company,
      'training_date3' => $faker->company,
      'has_insurance' => 0,
      'bank_name' => $faker->randomElement($array = array('국민은행','기업은행','농협','신한은행','하나은행'), $count=1),
      'bank_account' => $faker->bankAccountNumber,
      'remark' => $faker->paragraph($nbSentences = 2  , $variableNbSentences = true),
      'created_at' => now(),
      'updated_at' => now(),
    ];
});
