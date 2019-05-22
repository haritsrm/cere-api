<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tryout extends Model
{
    protected $fillable = [
        'name', 'lesson_id', 'class_id', 'instruction', 'duration', 'attempt_count', 'start_date', 'end_date', 'price', 'scoring_system'
    ];

    function cereouts()
    {
        $this->hasMany(Cereout::class);
    }

    function questions(){
    	$this->hasMany(Question::class);
    }
}
