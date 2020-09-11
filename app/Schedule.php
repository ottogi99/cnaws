<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'is_allow', 'is_period',
        'input_start_date', 'input_end_date',
    ];

    protected $dates = [
        'input_start_date', 'input_end_date',
    ];

    // protected $defaults = [
    //   'is_allow'          => '0',
    //   'is_period'         => '0',
    //   // 'input_start_date'  => now()->format('Y-m-d'),
    //   // 'input_end_date'    => now()->format('Y-m-d'),
    // ];
}
