<?php

namespace App\Http\Controllers\Services\Cereouts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Cereout;

class AnswerService extends Controller
{
    public function __construct()
    {
        $this->newAnswer = new Answer;
        $this->newCereout = new Cereout;
    }

    public function browse($cereout_id)
    {
        return $this->newCereout->find($Cereout_id)->answers()->paginate(10);
    }

    public function create(Array $req)
    {
        return $this->newAnswer->create($req);
    }

    public function find($id)
    {
        return $this->newAnswer->find($id);
    }

    public function findCereout($cereout_id)
    {
        return $this->newAnswer->where('cereout_id', $cereout_id)->get();
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
