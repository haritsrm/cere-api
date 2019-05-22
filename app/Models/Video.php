<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'title', 'video_url', 'section_id'
    ];

    function section()
    {
        $this->belongsTo(Section::class);
    }
}
