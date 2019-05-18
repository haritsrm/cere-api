<?php

namespace App\Http\Controllers\Services\Cereouts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cereout;
use App\Models\Tryout;

class CereoutService extends Controller
{
    public function __construct()
    {
        $this->newCereout = new Cereout;
        $this->newTryout = new Tryout;
    }

    public function browse($tryout_id)
    {
        return $this->newTryout->find($tryout_id)->favorites()->paginate(10);
    }

    public function create(Array $req)
    {
        return $this->newCereout->create($req);
    }

    public function find($id)
    {
        return $this->newCereout->find($id);
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
