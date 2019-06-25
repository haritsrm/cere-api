<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    protected $fillable = [
    	'cerepost_id', 'title', 'image', 'content', 'user_id'
    ];
}
