<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Master\GeneralInformationResource;
use App\Models\GeneralInformation;

class GeneralInformationController extends Controller
{
    //
    public function index()
    {
        $data = GeneralInformation::all();

        return GeneralInformationResource::collection($data);
    }
}
