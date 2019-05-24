<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Information;

class InformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'category' => $this->category,
            'url_photo' => $this->url_photo
        ];
    }
}
