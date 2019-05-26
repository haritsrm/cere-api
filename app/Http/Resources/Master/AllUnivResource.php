<?php

namespace App\Http\Resources\Master;

use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Http\Resources\Json\JsonResource;
class AllUnivResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $faculty = Faculty::where('univ_id',$this->id)->get();
        $department = Department::join('faculties','faculties.id','=','departments.faculty_id')
                ->select('departments.*','faculties.univ_id','faculties.name as faculty_name')
                ->where('faculties.univ_id',$this->id)->get();
        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'department'=> $department,
        ];
    }
}
