<?php

namespace App\Http\Controllers\Services\Cerevids;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Question;

class QuestionService extends Controller
{
    public function __construct()
    {
        $this->newQuestion = new Question;
    }

    public function browse($id)
    {
        return $this->newQuestion->find($id);
    }

    public function find($id)
    {
        return $this->newCourse->find($id);
    }
}
