<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserManual extends Model
{
    protected $table = 'user_manual';

    protected $fillable = ['title', 'content', 'hit'];

    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'suggestion_id', 'id');
    }
}
