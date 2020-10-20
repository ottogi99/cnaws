<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comment';
    protected $dateFormat = 'Y-m-d h:i:s';

    // protected $fillable = ['commentable_type', 'commentable_id', 'user_id', 'parent_id', 'content'];
    protected $fillable = ['commentable_id', 'user_id', 'content'];

    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commentable()
    {
        // return $this->morphTo();
        return $this->belongsTo(Suggestion::class);
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('Y-m-d h:i:s');
    }

    protected $appends = ['formatted_date'];

    // public function replies()
    // {
    //     return $this->hasMany(Comment::class, 'parent_id')->latest();
    // }

    // public function parent()
    // {
    //     return $this->belongsTo(Comment::class, 'parent_id', 'id');
    // }
}
