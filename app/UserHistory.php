<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserHistory extends Model
{
    protected $fillable = [
        // 'name', 'email', 'password',
        'worker_id',
        'target_id',
        'contents',
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function user_worker()
    {
        return $this->belongsTo(User::class, 'worker_id', 'nonghyup_id');  // (클래스, 로컬키(UserHistory), 외래키(User))
    }

    public function user_target()
    {
        return $this->belongsTo(User::class, 'target_id', 'nonghyup_id');  // (클래스, 로컬키(UserHistory), 외래키(User))
    }
}
