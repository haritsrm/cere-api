<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryCall extends Model
{
    //
    protected $fillable = [
        'student_id', 'teacher_id', 'rating','review'
    ];
}
