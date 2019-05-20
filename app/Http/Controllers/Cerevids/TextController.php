<?php

namespace App\Http\Controllers\Cerevids;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Cerevids\TextService;
use App\Http\Resources\Text\TextResource;

class TextController extends Controller
{
    public function __construct()
    {
        $this->text = new TextService;
    }

    public function index($section_id)
    {
        $texts = $this->text->browse($section_id);

        return TextResource::collection($texts);
    }

    public function create($section_id, Request $req)
    {
        $result = $this->text->create([
            'section_id' => $section_id,
            'title' => $req->title,
            'content' => $req->content
        ]);

        return new TextResource($result);
    }

    public function find($section_id, $text_id)
    {
        $text = $this->text->find($text_id);

        return new TextResource($text);
    }

    public function update($section_id, $text_id, Request $req)
    {
        $result = $this->text->update($text_id, [
            'title' => $req->title,
            'content' => $req->content
        ]);

        return $result;
    }

    public function delete($section_id, $text_id)
    {
        $result = $this->text->destroy($text_id);

        return $result;
    }
}
