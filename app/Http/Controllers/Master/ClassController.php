<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Master\ClassResource;
use App\Models\Kelas;

class ClassController extends Controller
{
    public function index()
    {
        $data = Kelas::all();

        return ClassResource::collection($data);
    }

}
