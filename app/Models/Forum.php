<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    protected $fillable = [
        'course_id', 'body', 'student_id', 'teacher_id',
    ];

    function course()
    {
        return $this->belongsTo(Course::class);
    }
}