<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'title', 'section_id'
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
