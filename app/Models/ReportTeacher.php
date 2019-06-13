<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportTeacher extends Model
{
    //
    protected $table = 'report_teacher';
    protected $fillable = [
        'student_id', 'teacher_id', 'report','image_url'
    ];
}
