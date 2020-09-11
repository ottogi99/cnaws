<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmallFarmer extends Model
{
    protected $fillable = [
        'business_year',
        'sigun_code', 'nonghyup_id',
        'name', 'age', 'sex', 'contact', 'address',
        'acreage1', 'acreage2', 'acreage3', 'sum_acreage',
        'remark'
    ];

    public function sigun()
    {
      return $this->belongsTo(Sigun::class, 'sigun_code', 'code');
    }

    public function nonghyup()
    {
        return $this->belongsTo(User::class, 'nonghyup_id', 'nonghyup_id');  // (클래스, 외래키, 로컬키)
    }

    // 빈 객체 생성시 초기값 지정
    // protected $defaults = [
    //   'sigun_code' => 'ca',
    //   'nonghyup_id' => 'annoymouse',
    //   'sex' => 'M',
    // ];

    // protected $attributes = [
    //     'sigun_code' => 'ca',
    //     'nonghyup_id' => 'annoymouse',
    //     'sex' => 'M',
    // ];
}
