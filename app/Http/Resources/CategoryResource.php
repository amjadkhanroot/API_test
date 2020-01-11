<?php

namespace App\Http\Resources;

use App\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'id'=> $this->id,
            'title'=> $this->title,
            'description'=> $this->decription,
            'tasks'=> TaskResource::collection($this->whenLoaded('tasks'))
        ];
    }
}
