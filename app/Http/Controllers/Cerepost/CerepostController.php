<?php

namespace App\Http\Controllers\Cerepost;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cerepost;
use App\Http\Resources\Cerepost\CerepostResource;

class CerepostController extends Controller
{
    public function index(){
    	$data = Cerepost::all();
    	return CerepostResource::collection($data);
    }
}
