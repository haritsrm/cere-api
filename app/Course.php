<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title', 'cover', 'description', 'curriculum', 'lesson_id', 'teacher_id'
    ];
}
