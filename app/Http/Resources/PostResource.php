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
        // return parent::toArray($request);
        return[
            'id'=>$this->id,
            'category_id'=>$this->category_id,
            'name_category'=>$this->catePost->name,
            // 'user_id'=>$this->Auth::user()->id,
            'title'=>$this->title,
            'short_des'=>$this->short_des,
            'content_post'=>$this->content_post,
            'image'=>$this->image,
            'meta_title'=>$this->meta_title,
            'meta_keywords'=>$this->meta_keywords,
            'meta_description'=>$this->meta_descriptions,
            'slug'=>$this->slug,
            'views'=>$this->views,
            'is_active'=>$this->is_active,
            'created_by'=> $this->createdBy($this->created_by)->name ?? null,
            'updated_by'=> $this->updatedBy($this->updated_by)->name ?? null,
            //'deleted_by'=>$this->title,
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
            // 'deleted_at'
        ];
    }
}
