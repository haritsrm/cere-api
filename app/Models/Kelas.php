<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    //
    protected $table ='classes';

    function lessons()
    {
        return $this->hasMany(Lesson::class, 'class_id', 'id');
    }
}
