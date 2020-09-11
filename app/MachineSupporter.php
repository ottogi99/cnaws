<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MachineSupporter extends Model
{
    protected $fillable = [
      'business_year',
      'sigun_code', 'nonghyup_id',
      'name', 'age', 'sex', 'contact', 'address',
      'machine1', 'machine2', 'machine3', 'machine4',
      'bank_name', 'bank_account',
      'remark',
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
      'nonghyup_id' => 'annoymouse'
    ];
}
