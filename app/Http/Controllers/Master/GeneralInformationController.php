<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Master\GeneralInformationResource;
use App\Models\GeneralInformation;
use App\Models\NominalTopUp;

class GeneralInformationController extends Controller
{
    //
    public function index()
    {
        $data = GeneralInformation::all();

        return GeneralInformationResource::collection($data);
    }

    public function getNominalTopUp(){
        $data = NominalTopUp::all();
        return response()->json([
                'status' => true,
                'data' => $data,
            ], 201);
    }
}
