<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Master\InformationResource;
use App\Models\Information;

class InformationController extends Controller
{
    //
    public function index()
    {
        $data = Information::all();

        return InformationResource::collection($data);
    }
}
