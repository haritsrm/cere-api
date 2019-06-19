<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionQuiz extends Model
{
    protected $fillable = [
        'question', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_answer', 'quiz_id'
    ];
}
