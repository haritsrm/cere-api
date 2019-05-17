<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cereout extends Model
{
    protected $fillable = [
        'tryout_id', 'student_id', 'point', 'passing_status'
    ];

    function course()
    {
        $this->belongsTo(Tryout::class);
    }
}
