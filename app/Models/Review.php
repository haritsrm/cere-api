<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'course_id', 'star', 'body', 'student_id',
    ];

    function course()
    {
        return $this->belongsTo(Course::class);
    }
}
