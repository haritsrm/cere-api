<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'cereout_id', 'question_id', 'answer'
    ];

    function cereout()
    {
        $this->belongsTo(Cereout::class);
    }
}
