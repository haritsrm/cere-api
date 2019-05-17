<?php

namespace App\Http\Controllers\Cerevids;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Cerevids\ReviewService;
use App\Http\Resources\Review\ReviewResource;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->review = new ReviewService;
    }

    public function index($course_id)
    {
        $reviews = $this->review->browse($course_id);

        return ReviewResource::collection($reviews);
    }

    public function create($course_id, Request $req)
    {
        $result = $this->review->create([
            'course_id' => $course_id,
            'star' => $req->star,
            'body' => $req->body,
            'student_id' => $req->student_id,
        ]);

        return new ReviewResource($result);
    }

    public function find($course_id, $review_id)
    {
        $review = $this->review->find($review_id);

        return new ReviewResource($review);
    }

    public function update($course_id, $review_id, Request $req)
    {
        $result = $this->review->update($review_id, [
            'star' => $req->star,
            'body' => $req->body,
        ]);

        return $result;
    }

    public function delete($course_id, $review_id)
    {
        $result = $this->review->destroy($review_id);

        return $result;
    }
}
