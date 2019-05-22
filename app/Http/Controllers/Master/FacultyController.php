<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Master\FacultyResource;
use App\Models\Faculty;

class FacultyController extends Controller
{
    //
    public function index(){
   	 	$data = Faculty::join('universities','universities.id','=','faculties.univ_id')
                    ->select('faculties.*','universities.name as university')->get();

        return FacultyResource::collection($data);
    }
}
