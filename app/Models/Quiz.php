<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'question', 'option_1', 'option_2', 'option_3', 'option_4', 'answer', 'section_id'
    ];

    function section()
    {
        $this->belongsTo(Section::class);
    }

    function lastSeen()
    {
        return $this->hasMany(LastSeen::class);
    }
}
