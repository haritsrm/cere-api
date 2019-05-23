<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cereout extends Model
{
    protected $fillable = [
        'tryout_id', 'user_id', 'my_time', 'score', 'total_answer', 'correct_answered', 'incorrect_answered', 'left_answered', 'result_status'
    ];

    function tryout()
    {
        return $this->belongsTo(Tryout::class);
    }

    function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
