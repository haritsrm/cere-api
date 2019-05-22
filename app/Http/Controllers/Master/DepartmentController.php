<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Master\DepartmentResource;
use App\Models\Department;

class DepartmentController extends Controller
{
    //
    public function index()
    {
        $data = Department::join('faculties','faculties.id','=','departments.faculty_id')
                    ->select('departments.*','faculties.name as faculty')->get();

        return DepartmentResource::collection($data);
    }
}
