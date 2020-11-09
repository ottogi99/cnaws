<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmallFarmer extends Model
{
    use FullTextSearch;

    protected $fillable = [
        'business_year',
        'sigun_code', 'nonghyup_id',
        'name', 'birth', 'age', 'sex', 'contact', 'address',
        'acreage1', 'acreage2', 'acreage3', 'sum_acreage',
        'remark'
    ];

    protected $searchable = [
        'name'
    ];

    public function sigun()
    {
      return $this->belongsTo(Sigun::class, 'sigun_code', 'code');
    }

    public function nonghyup()
    {
        return $this->belongsTo(User::class, 'nonghyup_id', 'nonghyup_id');  // (클래스, 외래키, 로컬키)
    }

    public function phoneNumber(){
        $phone = $this->contact;
        $phone = preg_replace("/[^0-9]/", "", $phone);
        $length = strlen($phone);

        switch($length){
          case 11 :
              return preg_replace("/([0-9]{3})([0-9]{4})([0-9]{4})/", "$1-$2-$3", $phone);
              break;
          case 10:
              return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);
              break;
          default :
              return $phone;
              break;
        }
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
