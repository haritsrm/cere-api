<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [
        'course_id', 'student_id'
    ];

    function course()
    {
        $this->belongsTo(Course::class);
    }
}
