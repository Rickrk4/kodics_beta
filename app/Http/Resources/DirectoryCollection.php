<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DirectoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $dirCollection = parent::toArray($request);
        //$dirCollection->links['parent_dir'] = '$this->parent_dir';
        return $dirCollection;
    }
}
