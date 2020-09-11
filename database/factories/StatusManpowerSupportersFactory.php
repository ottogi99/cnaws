<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\StatusManpowerSupporter;
use Faker\Generator as Faker;

$factory->define(StatusManpowerSupporter::class, function (Faker $faker) {
    $nonghyup = App\User::find($faker->numberBetween(1,10));
    $sigun_code = $nonghyup->sigun->code;
    $nonghyup_id = $nonghyup->nonghyup_id;

    // 지원농가
    $farmer = App\LargeFarmer::firstWhere('nonghyup_id', $nonghyup_id);
    $farmer_id = ($farmer) ? $farmer->id : $faker->numberBetween(1,10);

    // 지원작업
    $supporter = App\ManpowerSupporter::firstWhere('nonghyup_id', $nonghyup_id);
    $supporter_id = ($supporter) ? $supporter->id : $faker->numberBetween(1,10);

    $working_days = $faker->numberBetween($min=0, $max=4);
    $job_start_date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = 'Asia/Seoul')->format('Y-m-d');
    $add_days = sprintf('+%d days', $working_days);
    $job_end_date = date('Y-m-d', strtotime($job_start_date.$add_days));
    $working_days = $working_days + 1;

    // 지급내역
    $payment_sum = $faker->numberBetween($min=0, $max=1000000);
    $payment_do = $payment_sum * 0.21;
    $payment_sigun = $payment_sum * 0.49;
    $payment_center = $payment_sum * 0.2;
    $payment_unit = $payment_sum * 0.1;

    return [
      'business_year' => $faker->numberBetween(2019,2020),
      'sigun_code' => $sigun_code,
      'nonghyup_id' => $nonghyup_id,
      // 지원농가
      'farmer_id' => $farmer_id,
      //지원작업
      'supporter_id' => $supporter_id,
      'job_start_date' => $job_start_date,
      'job_end_date' => $job_end_date,
      'working_days' => $working_days,
      'work_detail' => $faker->word,
      // 지급내역
      'recipient' => $faker->randomElement($array=array('S','F')),
      'payment_item1' => 5000 * $working_days,  // 교통비
      'payment_item2' => 3000 * $working_days,  // 간식비
      'payment_item3' => 2000 * $working_days,  // 마스크구입비
      // 지급액
      'payment_sum' => $payment_sum,
      'payment_do' => $payment_do,
      'payment_sigun' => $payment_sigun,
      'payment_center' => $payment_center,
      'payment_unit' => $payment_unit,
      'remark' => '',
    ];
});
