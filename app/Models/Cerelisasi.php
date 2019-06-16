<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cerelisasi extends Model
{
    protected $table = 'cerelisasi_data';
    protected $fillable = [
        'user_id', 'department_id', 'total_point', 'status'
    ];

    function users()
    {
        return $this->hasMany(User::class);
    }

    function departments()
    {
        return $this->hasMany(Department::class);
    }
}
