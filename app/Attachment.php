<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $table = 'attachment';
    // protected $fillable = ['filename', 'bytes', 'mime'];
    protected $fillable = ['stored_name', 'original_name', 'bytes', 'mime'];

    public function notice()
    {
        return $this->belongsTo(Notice::class);
    }

    public function manual()
    {
        return $this->belongsTo(UserManual::class);
    }

    public function suggestion()
    {
        return $this->belongsTo(Suggestion::class);
    }

    public function getBytesAttribute($value)
    {
        return format_filesize($value);
    }

    public function getUrlAttribute()
    {
        return url('files/'.$this->filename);
    }
}
