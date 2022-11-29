<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoadPostResource extends JsonResource
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
        if(!empty($request)){
            $postData = [];
            if(!empty($this->posts)){
                foreach($this->posts as $key => $post){
                    $postData[$key]['id'] = $post->id;
                    $postData[$key]['author'] = $post->createdBy->name ?? null;
                    $postData[$key]['title'] = $post->title;
                    $postData[$key]['short_des'] = $post->short_des;
                    $postData[$key]['content_post'] = $post->content_post;
                    $postData[$key]['image'] = $post->image;
                    $postData[$key]['meta_title'] = $post->meta_title;
                    $postData[$key]['meta_keywords'] = $post->meta_keywords;
                    $postData[$key]['meta_description'] = $post->meta_description;
                    $postData[$key]['slug'] = $post->slug;
                    $postData[$key]['view'] = $post->view;
                    $postData[$key]['is_feature'] = $post->is_feature;
                    $postData[$key]['is_active'] = $post->is_active;
                    $postData[$key]['updated_at'] = $post->updated_at->format('Y-m-d H:i:s');
                    $postData[$key]['updated_by'] = $post->updateBy->name ?? null;
                }
            }
            return [
                'id'=>$this->id,
                'name'=>$this->name,
                'slug'=>$this->slug,
                'is_active'=>$this->is_active,
                'posts' => $postData ?? null,
                'created_at' => $this->created_at->format('d-m-Y H:i:s'),
                'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
                'created_by'    => $this->createdBy->name ?? null,
                'updated_by'    => $this->updatedBy->name ?? null,
            ];
        }

       return [];
    }
}
