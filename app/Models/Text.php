<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Text extends Model
{
    protected $fillable = [
        'title', 'content', 'section_id'
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
