<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Directory extends JsonResource
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
            'url' => "d/$this->id",
            'title' => $this->name,
            'coverUrl' => "g/$this->cover_id",
            'type' => 'd',
            'id' => $this->id
        ];
        return parent::toArray($request);
    }
}
