<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if(!$this->collection->isEmpty()){
            foreach($this->collection as $value){
                $result['data'][] = [
                    'id'            => $value->id,
                    'category_id'   => $value->category_id,
                    // 'name_category' => $value->catePost->name,
                    'title'          => $value->title,
                    'content_post'       => $value->content_post,
                    'short_des'          => $value->short_des,
                    'meta_title'          => $value->meta_title,
                    'meta_keywords'          => $value->meta_keywords,
                    'meta_description'          => $value->meta_description,
                    'slug'          => $value->slug,
                    'views'          => $value->views,
                    'is_active'     => $value->is_active,
                    'created_at'    => $value->created_at->format('Y-m-d H:i:s'),
                    'updated_at'    => $value->updated_at->format('Y-m-d H:i:s'),
                    'created_by'    => $value->createdBy->name ?? null,
                    'updated_by'    => $value->updatedBy->name ?? null,
                ];
            }
            $result['paginator'] = [
                'currentPage' => $this->currentPage(),
                'totalPages' => $this->lastPage(),
                'perPage' => $this->perPage(),
                'count' => $this->count(),
                'total' => $this->total(),
                'nextPageUrl' => $this->nextPageUrl(),
                'prevPageUrl' => $this->previousPageUrl(),
            ];
            return $result;
        }

        // $request is empty
        $result['data'] = [];
        $result['paginator'] = [];
        return $result;
    }
}
