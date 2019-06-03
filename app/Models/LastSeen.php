<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LastSeen extends Model
{
    protected $fillable = [
        'video_id', 'quiz_id', 'text_id', 'user_id'
    ];

    function video()
    {
        return $this->belongsTo(Video::class);
    }

    function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    function text()
    {
        return $this->belongsTo(Text::class);
    }
}
