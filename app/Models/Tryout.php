<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tryout extends Model
{
    protected $fillable = [
        'name', 'passing_percentage', 'instruction', 'duration', 'class', 'attempt_count', 'start_date', 'end_date', 'expire_days', 'price'
    ];

    function cereouts()
    {
        $this->hasMany(Cereout::class);
    }

    function questions(){
    	$this->hasMany(Question::class);
    }
}
