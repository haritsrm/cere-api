<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $fillable = [
    	'post_id', 'comment_id', 'user_id', 'content'
    ];
}
