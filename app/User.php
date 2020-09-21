<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; //softDelete(database/migrations/user.php)


class User extends Authenticatable
{
    use Notifiable;
    // use SoftDeletes;  //소프트 삭제하기

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // 'name', 'email', 'password',
        'sigun_code',
        'nonghyup_id', 'password',
        'name', 'address', 'contact', 'representative',
        'activated', 'is_admin', 'sequence'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];


    public function sigun()
    {
        return $this->belongsTo(Sigun::class, 'sigun_code', 'code');  // (클래스, 외래키(UserDB), 로컬키(SigunDB) )
    }

    // public function activated_user()
    // {
    //     return $this->hasMany(ActivatedUser::class, 'nonghyup_id', 'nonghyup_id');
    // }
    //
    // 빈 객체 생성시 초기값 지정
    protected $defaults = [
      'sigun_code' => 'ca',
      // 'activated' => '0'
    ];

    public function isAdmin()
    {
        return ($this->is_admin) ? true : false;
    }

    // public getBusinessYearAttribute()
    // {
    //     return $this->activated_user()->
    // }
}
