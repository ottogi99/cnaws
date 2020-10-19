<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $table = 'notice';

    protected $fillable = ['title', 'content', 'hit'];

    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    // public function comments()
    // {
    //     return $this->morphMany(Comment::class, 'commentable');
    //     // return $this->hasMany(Comment::class);
    // }
}
