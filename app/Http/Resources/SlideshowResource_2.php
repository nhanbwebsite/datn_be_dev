<?php

namespace App\Http\Resources;

use App\Models\Slideshow;
use Illuminate\Http\Resources\Json\JsonResource;

class SlideshowResource_2 extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if(!empty($request)){
            return [
                'id'            => $this->id,
                'title'          => $this->title,
                'slug'           => $this->slug,
                'details'        => Slideshow::getDetailById($this->id),
                'is_active'     => $this->is_active,
                'created_at'    => $this->created_at->format('Y-m-d H:i:s'),
                'updated_at'    => $this->updated_at->format('Y-m-d H:i:s'),
                'created_by'    => $this->createdBy->name ?? null,
                'updated_by'    => $this->updatedBy->name ?? null,
            ];
        }

        return [];
    }
}
