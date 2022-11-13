<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostCategoriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
       // return parent::toArray($request);
       return[
        'id'=>$this->id,
        'name'=>$this->name,
        'slug'=>$this->slug,
        'is_active'=>$this->is_active,
        'created_by'=>$this->id,
        'updated_by'=>$this->id,
        'created_by'=> $this->createdBy($this->created_by)->name ?? null,
        'updated_by'=> $this->updatedBy($this->updated_by)->name ?? null,
        'created_at' => $this->created_at->format('d-m-Y H:i:s'),
        'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
        // 'deleted_at'=>$this->id,
       ];
    }
}
