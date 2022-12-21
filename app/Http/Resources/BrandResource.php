<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
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
                'brand_name'    =>$this->brand_name,
                'slug'          =>$this->slug,
                'is_active'     => $this->is_active,
                'is_post'       => $this->is_post,
                'created_at'    => $this->created_at->format('d-m-Y H:i:s'),
                'updated_at'    => $this->updated_at->format('d-m-Y H:i:s'),
                'created_by'    => $this->createdBy->name ?? null,
                'updated_by'    => $this->updatedBy->name ?? null,
            ];
        }

        return [];

    }
}
