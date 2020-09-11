<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\StatusMachineSupporter;
use Faker\Generator as Faker;
// use Carbon\Carbon;

$factory->define(StatusMachineSupporter::class, function (Faker $faker) {
    $nonghyup = App\User::find($faker->numberBetween(1,10));
    $sigun_code = $nonghyup->sigun->code;
    $nonghyup_id = $nonghyup->nonghyup_id;

    // $farmer = App\SmallFarmer::firstWhere('sigun_code', $sigun_code);
    $farmer = App\SmallFarmer::firstWhere('nonghyup_id', $nonghyup_id);
    $farmer_id = ($farmer) ? $farmer->id : $faker->numberBetween(1,10);
    // $farmer_name = $farmer->name;
    // $farmer_address = $farmer->address;
    // $farmer_sex = $farmer->sex;
    // $farmer_name = $faker->name;
    // $farmer_address = $faker->address;
    // $farmer_sex = $faker->randomElement($array=array('M','F'));

    $supporter = App\MachineSupporter::firstWhere('nonghyup_id', $nonghyup_id);
    $supporter_id = ($supporter) ? $supporter->id : $faker->numberBetween(1,10);
    // $supporter = App\MachineSupporter::find($supporter_id);
    // $supperter_name = $supporter->name;

    // $working_days = $faker->randomDigitNotNull;
    $working_days = $faker->numberBetween($min=0, $max=4);
    $job_start_date = $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = 'Asia/Seoul')->format('Y-m-d');
    $add_days = sprintf('+%d days', $working_days);
    // $job_end_date = $job_start_date->modify($add_days);
    $job_end_date = date('Y-m-d', strtotime($job_start_date.$add_days));
    $working_days = $working_days + 1;

    // $start  = new Carbon($job_start_date);
    // $end    = new Carbon($job_end_date);
    // $working_days = $start->diffInDays($end)+1;//->format('%H:%I:%S');

    $payment_sum = $faker->numberBetween($min=0, $max=1000000);
    $payment_do = $payment_sum * 0.21;
    $payment_sigun = $payment_sum * 0.49;
    $payment_center = $payment_sum * 0.2;
    $payment_unit = $payment_sum * 0.1;

    // $job_start_date = $faker->dateTimeBetween($startDate = '-1 months', $endDate = 'now', $timezone = 'Asia/Seoul')->format('Y-m-d');
    // $interval = sprintf("+ %s days", $faker->numberBetween($min=0, $max=5));
    // $job_end_date = $faker->dateTimeInInterval($startDate = $job_start_date, $interval = $interval, $timezone = 'Asia/Seoul')->format('Y-m-d');

    // $start  = new Carbon('2018-10-04 15:00:03');
    // $end    = new Carbon('2018-10-05 17:00:09');
    // dd($job_start_date);
    // $start  = date("Y-m-d", strtotime($job_start_date));
    // $end    = date("Y-m-d", strtotime($job_end_date));

    return [
      'business_year' => $faker->numberBetween(2019,2020),
      'sigun_code' => $sigun_code,
      'nonghyup_id' => $nonghyup_id,
      // 지원농가
      'farmer_id' => $farmer_id,
      // 'name' => $farmer_name,        //지원농가명
      // 'address' => $farmer_address,  //지원농가 주소
      // 'sex' => $farmer_sex,          //지원농가 성별
      //지원작업
      'supporter_id' => $supporter_id,
      'job_start_date' => $job_start_date,
      'job_end_date' => $job_end_date,
      'working_days' => $working_days,
      'work_detail' => $faker->word,
      'working_area' => $faker->randomFloat($nbMaxDecimals=1, $min=1, $max=1000),
      // 'payment_date' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = 'Asia/Seoul'),
      'payment_sum' => $payment_sum,
      'payment_do' => $payment_do,
      'payment_sigun' => $payment_sigun,
      'payment_center' => $payment_center,
      'payment_unit' => $payment_unit,
      'remark' => '',
      // 'remark' => $faker->paragraph($nbSentences = 2, $variableNbSentences = true),
      // 'created_at' => now(),
      // 'updated_at' => now(),
    ];
});
