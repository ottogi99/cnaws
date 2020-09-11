<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sigun extends Model
{
    //
    protected $fillable = ['code', 'sequence', 'name'];

    public function users()
    {
        return $this->hasMany(User::class, 'code', 'sigun_code');   // (클래스, 외부키, 로컬키)
    }
}
