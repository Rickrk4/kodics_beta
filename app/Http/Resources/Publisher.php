<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Publisher extends JsonResource
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
            "name" => $this->name,
            "foundation_date" => $this->found_at,
            "description" => $this->description
        ];
        return parent::toArray($request);
    }
}
