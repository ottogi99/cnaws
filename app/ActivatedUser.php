<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivatedUser extends Model
{
    protected $fillable = [
        'business_year', 'nonghyup_id',
    ];

    public function nonghyup()
    {
        return $this->belongsTo(User::class, 'nonghyup_id', 'nonghyup_id');  // (클래스, 외래키(UserDB), 로컬키(SigunDB) )
    }
}
