<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    //
    protected $fillable = [
        'tryout_id', 'question', 'option_a', 'option_b', 'option_c', 'option_c', 'option_d', 'option_e', 'option_f', 'correct_answer', 'explanation','url_explanation', 'correct_score', 'incorrect_score'
    ];

    function tryout(){
    	$this->belongsTo(Tryout::class);
    }
}
