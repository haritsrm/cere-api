<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cerevid extends Model
{
    protected $fillable = [
        'course_id', 'user_id'
    ];

    function course()
    {
        $this->belongsTo(Course::class);
    }
}
