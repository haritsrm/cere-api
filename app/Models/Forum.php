<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    protected $fillable = [
        'course_id', 'body', 'user_id', 'forum_id',
    ];

    function course()
    {
        return $this->belongsTo(Course::class);
    }

    function forums()
    {
        return $this->hasMany(Forum::class);
    }
}
