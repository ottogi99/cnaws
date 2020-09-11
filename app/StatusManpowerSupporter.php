<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusManpowerSupporter extends Model
{
    protected $fillable = [
      'business_year',                          // 대상년도
      'sigun_code', 'nonghyup_id',
      'farmer_id', 'supporter_id',
      // 'name', 'address', 'sex', 'supporter',
      'job_start_date', 'job_end_date', 'working_days',
      'work_detail', 'recipient',
      'payment_item1', 'payment_item2', 'payment_item3',
      'payment_sum', 'payment_do', 'payment_sigun', 'payment_center', 'payment_unit',   // 지급액(합계/도비/시군비/중앙회/지역농협)
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

    public function farmer()
    {
        return $this->belongsTo(LargeFarmer::class, 'farmer_id', 'id');
    }

    public function supporter()
    {
        return $this->belongsTo(ManpowerSupporter::class, 'supporter_id', 'id');
    }

    // 빈 객체 생성시 초기값 지정
    protected $defaults = [
      'sigun_code' => 'ca',
      'nonghyup_id' => 'annoymouse',
    ];

    protected $dates = [
        'job_start_date', 'job_end_date',
    ];
}
