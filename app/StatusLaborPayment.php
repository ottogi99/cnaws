<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusLaborPayment extends Model
{
    protected $fillable = [
      'business_year',                          // 대상년도
      'sigun_code', 'nonghyup_id',
      'name', 'birth', //'contact',
      'bank_name', 'bank_account',
      'detail', 'payment_date',
      'payment_sum', 'payment_do', 'payment_sigun', 'payment_center', 'payment_unit',
      'remark'
    ];

    public function sigun()
    {
        return $this->belongsTo(Sigun::class, 'sigun_code', 'code');
    }

    public function nonghyup()
    {
        return $this->belongsTo(User::class, 'nonghyup_id', 'nonghyup_id');  // (클래스, 외래키(UserDB), 로컬키(SigunDB) )
    }

    // 빈 객체 생성시 초기값 지정
    // protected $defaults = [
    //   'sigun_code' => 'ca',
    //   'nonghyup_id' => 'annoymouse',
    // ];

    protected $dates = [
        'payment_date'
    ];
}
