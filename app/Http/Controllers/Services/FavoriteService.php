<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Course;

class FavoriteService extends Controller
{
    public function __construct()
    {
        $this->newFavorite = new Favorite;
        $this->newCourse = new Course;
    }

    public function browse($course_id)
    {
        return $this->newCourse->find($course_id)->favorites()->paginate(10);
    }

    public function create(Array $req)
    {
        return $this->newFavorite->create($req);
    }

    public function find($id)
    {
        return $this->newFavorite->find($id);
    }

    public function destroy($id)
    {
        $this->find($id)->delete();
    }
}
