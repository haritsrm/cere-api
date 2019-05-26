<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Master\UniversityResource;
use App\Http\Resources\Master\AllUnivResource;
use App\Models\University;

class UniversityController extends Controller
{
    //
    public function index()
    {
        $data = University::all();
        return UniversityResource::collection($data);
    }

    public function getAllData(){
    	$data = University::all();
    	return AllUnivResource::collection($data);			
    }
}
