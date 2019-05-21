<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'title', 'course_id'
    ];

    function course()
    {
        return $this->belongsTo(Course::class);
    }

    function videos()
    {
        return $this->hasMany(Video::class);
    }

    function texts()
    {
        return $this->hasMany(Text::class);
    }

    function quiz()
    {
        return $this->hasMany(Quiz::class);
    }
}
