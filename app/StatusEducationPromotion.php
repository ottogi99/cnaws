<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusEducationPromotion extends Model
{
    protected $fillable = [
      'business_year',                          // 대상년도
      'sigun_code', 'nonghyup_id',
      'item', 'target', 'detail',               // 지급항목, 지급대상, 지급내용
      'payment_date',                           // 지급일자
      'payment_sum',
      'payment_do', 'payment_sigun', 'payment_center', 'payment_unit',   // 지급액(합계/도비/시군비/중앙회/지역농협)
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
    protected $defaults = [
        'sigun_code' => 'ca',
        'nonghyup_id' => 'annoymouse',
    ];

    protected $dates = [
        'payment_date'
    ];
}
