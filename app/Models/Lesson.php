<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'class_id', 'name', 'passing_percentage'
    ];

    function courses()
    {
        return $this->hasMany(Course::class);
    }

    function class()
    {
        return $this->belongsTo(Classroom::class);
    }
}
