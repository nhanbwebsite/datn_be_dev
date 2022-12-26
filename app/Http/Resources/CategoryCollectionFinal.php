<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollectionFinal extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        if(!$this->collection->isEmpty()){
            // $request not empty
            foreach($this->collection as $value){
                $slideshow = [];
                if(!$value->slideshowBycate->isEmpty()){
                    foreach($value->slideshowBycate as $k => $item){
                        // dd($item);
                        $slideshow[$k]['id'] = $item->id;
                        $slideshow[$k]['category_id'] = $value->id;
                        $slideshow[$k]['title'] = $item->title;
                        $slideshow[$k]['slug'] = $item->slug;
                        $slideshow[$k]['is_active'] = $item->is_active;
                        $slideshow[$k]['created_at'] = $item->created_at->format('Y-m-d H:i:s');
                        $slideshow[$k]['updated_at'] = $item->updated_at->format('Y-m-d H:i:s');
                        $slideshow[$k]['created_by'] = $item->createdBy->name ?? null;
                        $slideshow[$k]['updated_by'] = $item->updatedBy->name ?? null;
                    }
                    $result['data'][] = [
                        'id'            => $value->id,
                        'name'          => $value->name,
                        'slug'          => $value->slug,
                        'url_img'       => $value->url_img,
                        'is_active'     => $value->is_active,
                        'slideshow'     => $slideshow,
                        'created_at'    => $value->created_at->format('Y-m-d H:i:s'),
                        'updated_at'    => $value->updated_at->format('Y-m-d H:i:s'),
                        'created_by'    => $value->createdBy->name ?? null,
                        'updated_by'    => $value->updatedBy->name ?? null,
                    ];

                }

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
