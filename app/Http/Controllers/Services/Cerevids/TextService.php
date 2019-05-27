<?php

namespace App\Http\Controllers\Services\Cerevids;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Text;

class TextService extends Controller
{
    public function __construct()
    {
        $this->newText = new Text;
        $this->newSection = new Section;
    }

    public function browse($course_id)
    {
        return $this->newSection->find($course_id)->texts()->get();
    }

    public function create(Array $req)
    {
        return $this->newText->create($req);
    }

    public function find($id)
    {
        return $this->newText->find($id);
    }

    public function update($id, Array $req)
    {
        $this->find($id)->update($req);
    }

    public function destroy($id)
    {
        $this->find($id)->delete();
    }
}
