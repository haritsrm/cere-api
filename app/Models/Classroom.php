<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'name_class',
    ];

    function lessons()
    {
        return $this->hasMany(Lesson::class, 'class_id', 'id');
    }
}
