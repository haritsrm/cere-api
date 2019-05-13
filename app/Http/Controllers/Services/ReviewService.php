<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Course;

class ReviewService extends Controller
{
    public function __construct()
    {
        $this->newReview = new Review;
        $this->newCourse = new Course;
    }

    public function browse($course_id)
    {
        return $this->newCourse->find($course_id)->reviews()->paginate(10);
    }

    public function create(Array $req)
    {
        return $this->newReview->create($req);
    }

    public function find($id)
    {
        return $this->newReview->find($id);
    }

    public function update($id, Array $req)
    {
        $this->find($id)->update($req);
    }

    public function destroy($id)
    {
        $this->find($id)->delete();
    }
}
