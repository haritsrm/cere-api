<?php

namespace App\Http\Resources\Tryout;

use Illuminate\Http\Resources\Json\JsonResource;

class TryoutResource extends JsonResource
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
            'name' => $this->name,
            'question' => $this->questions()
        ];
    }
}
