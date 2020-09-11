<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    //
    protected $fillable = [
        'sigun_code', 'nonghyup_id',
        'business_year', 'amount'
    ];//, 'updated_at', 'deleted_at'];

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
      'nh_id' => 'annoymouse'
    ];
}
