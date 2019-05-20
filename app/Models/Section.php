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
}
