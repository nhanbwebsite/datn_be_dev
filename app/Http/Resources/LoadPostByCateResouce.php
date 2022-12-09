<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoadPostByCateResouce extends JsonResource
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
            $posts = [];
            if(!empty($this->posts)){
                foreach($this->posts as $key => $postsByCate){
                    $posts[$key]['id'] = $postsByCate->id;
                    $posts[$key]['subcategory_id'] = $postsByCate->subcategory_id;
                    $posts[$key]['user_id'] = $postsByCate->user_id;
                    $posts[$key]['title'] = $postsByCate->title;
                    $posts[$key]['image'] = $postsByCate->image;
                    $posts[$key]['short_des'] = $postsByCate->short_des;
                    $posts[$key]['content_post'] = $postsByCate->content_post;
                    $posts[$key]['meta_title'] = $postsByCate->meta_title;
                    $posts[$key]['meta_keywords'] = $postsByCate->meta_keywords;
                    $posts[$key]['views'] = $postsByCate->views;
                    $posts[$key]['is_feature'] = $postsByCate->is_feature;
                    $posts[$key]['slug'] = $postsByCate->slug;
                    $posts[$key]['is_active'] =$postsByCate->is_active;
                    $posts[$key]['updated_at'] = $postsByCate->updated_at->format('Y-m-d H:i:s');
                    $posts[$key]['updated_by'] = $postsByCate->updateBy->name ?? null;
                }
            }
            return [
                'id'=>$this->id,
                'name'=>$this->name,
                'brand_id'=>$this->brand_id,
                'slug'=>$this->slug,
                'is_active'=>$this->is_active,
                'post' => $posts?? null,
                'created_at' => $this->created_at->format('d-m-Y H:i:s'),
                'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
                'created_by'    => $this->createdBy->name ?? null,
                'updated_by'    => $this->updatedBy->name ?? null,
            ];
        }

       return [];
    }
}
