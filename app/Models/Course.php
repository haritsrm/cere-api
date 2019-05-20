<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title', 'cover', 'description', 'curriculum', 'lesson_id', 'teacher_id'
    ];

    function sections()
    {
        return $this->hasMany(Section::class);
    }

    function reviews()
    {
        return $this->hasMany(Review::class);
    }

    function forums()
    {
        return $this->hasMany(Forum::class);
    }

    function cerevids()
    {
        return $this->hasMany(Cerevid::class);
    }

    function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
