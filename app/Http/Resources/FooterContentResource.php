<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FooterContentResource extends JsonResource
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
                'id'=>$this->id,
                'footer_category_id'=>$this->category_id,
                'name_footer'=>$this->category->name,
                'title'=>$this->title,
                'content'=>$this->content,
                'slug'=>$this->slug,
                'is_active'=>$this->is_active,
                'created_at' => $this->created_at->format('d-m-Y H:i:s'),
                'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
                'created_by'    => $this->createdBy->name ?? null,
                'updated_by'    => $this->updatedBy->name ?? null,
            ];
        }

       return [];
    }
}
