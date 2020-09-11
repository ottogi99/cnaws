<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\StatusLaborPayment;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(StatusLaborPayment::class, function (Faker $faker) {
    $nonghyup_id = $faker->numberBetween(1,10);
    $sum = $faker->numberBetween($min=0, $max=10000000);
    // $job_start_date = $faker->dateTimeBetween($startDate = '-1 months', $endDate = 'now', $timezone = 'Asia/Seoul')->format('Y-m-d');
    // $interval = sprintf("+ %s days", $faker->numberBetween($min=0, $max=5));
    // $job_end_date = $faker->dateTimeInInterval($startDate = $job_start_date, $interval = $interval, $timezone = 'Asia/Seoul')->format('Y-m-d');

    // $start  = new Carbon('2018-10-04 15:00:03');
    // $end    = new Carbon('2018-10-05 17:00:09');
    // dd($job_start_date);
    // $start  = date("Y-m-d", strtotime($job_start_date));
    // $end    = date("Y-m-d", strtotime($job_end_date));

    // $start  = new Carbon($job_start_date);
    // $end    = new Carbon($job_end_date);
    // $diff = $start->diffInDays($end)+1;//->format('%H:%I:%S');

    $birth_year = $faker->dateTimeBetween($startDate = '-100 years', $endDate = '-10 years', $timezone = 'Asia/Seoul')->format('Y');
    $birth_month = sprintf('%02d', $faker->numberBetween(1,12));
    $birth_day = sprintf('%02d', $faker->numberBetween(1,31));
    $birth = sprintf('%s%s%s%s', $birth_year, $birth_month, $birth_day, $faker->numberBetween(1,2));

    $bankAccountNumber = str_insert_pattern($faker->bankAccountNumber, 4, '-');

    return [
        'business_year' => $faker->numberBetween(2019,2020),
        'sigun_code' => function () use ($nonghyup_id){
            return App\User::find($nonghyup_id)->sigun->code;
        },
        'nonghyup_id' => function () use ($nonghyup_id) {
            return App\User::find($nonghyup_id)->nonghyup_id;
        },
        'name' => $faker->name,
        'birth' => $birth,
        // 'contact' => $faker->unique()->regexify('^01(0|1|6|7|8|9)([0-9]{3,4})([0-9]{4})$'),
        'bank_name' => $faker->randomElement($array = array('국민은행','기업은행','농협','신한은행','하나은행'), $count=1),
        'bank_account' => $bankAccountNumber,
        // 'job_start_date' => $job_start_date,
        // 'job_end_date' => $job_end_date,
        // 'working_days' => $diff,
        'detail' => $faker->sentence($nbWords = 2, $variableNbWords = true),
        'payment_date' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = 'Asia/Seoul')->format('Y-m-d'),
        'payment_sum' => $sum,
        'payment_do' => $sum * 0.21,
        'payment_sigun' => $sum * 0.49,
        'payment_center' => $sum * 0.2,
        'payment_unit' => $sum * 0.1,
        // 'remark' => $faker->paragraph($nbSentences = 2, $variableNbSentences = true),
        'remark' => '',
        // 'created_at' => now(),
        // 'updated_at' => now(),
    ];
});
