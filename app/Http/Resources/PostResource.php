<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PostResource extends JsonResource
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
                'author'        => $this->createdBy->name ?? null,
                'subcategory_id'   => $this->subcategory_id,
                'subcategory_name'   => $this->subcategory->name,
                'title'          => $this->title,
                'short_des'          => $this->short_des,
                'content_post'       => $this->content_post,
                'image'       => $this->image ?? null,
                'meta_title'          => $this->meta_title,
                'meta_keywords'          => $this->meta_keywords,
                'meta_description'          => $this->meta_description,
                'slug'          => $this->slug,
                'views'          => $this->views,
                'is_active'     => $this->is_active,
                'created_at'    => $this->created_at->format('d-m-Y H:i:s'),
                'updated_at'    => $this->updated_at->format('d-m-Y H:i:s'),
                'updated_by'    => $this->updatedBy->name ?? null,
            ];
        }
        return [];
    }
}
