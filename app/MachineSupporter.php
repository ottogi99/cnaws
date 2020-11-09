<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MachineSupporter extends Model
{
    use FullTextSearch;

    protected $fillable = [
      'business_year',
      'sigun_code', 'nonghyup_id',
      'name', 'birth', 'age', 'sex', 'contact', 'address',
      'machine1', 'machine2', 'machine3', 'machine4',
      'bank_name', 'bank_account',
      'remark',
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
        return $this->belongsTo(User::class, 'nonghyup_id', 'nonghyup_id');  // (클래스, 외래키(UserDB), 로컬키(SigunDB) )
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
    protected $defaults = [
      'sigun_code' => 'ca',
      'nonghyup_id' => 'annoymouse'
    ];
}
