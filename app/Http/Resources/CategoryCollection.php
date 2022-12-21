<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
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
                $subcategoryData = [];
                if(!$value->subs->isEmpty()){
                    foreach($value->subs as $k => $item){
                        // dd($item);
                        $subcategoryData[$k]['id'] = $item->id;
                        $subcategoryData[$k]['category_id'] = $value->id;
                        $subcategoryData[$k]['name'] = $item->name;
                        $subcategoryData[$k]['slug'] = $item->slug;
                        $subcategoryData[$k]['url_img'] = $item->url_img ?? null;
                        $subcategoryData[$k]['is_active'] = $item->is_active;
                        $subcategoryData[$k]['created_at'] = $item->created_at->format('Y-m-d H:i:s');
                        $subcategoryData[$k]['updated_at'] = $item->updated_at->format('Y-m-d H:i:s');
                        $subcategoryData[$k]['created_by'] = $item->createdBy->name ?? null;
                        $subcategoryData[$k]['updated_by'] = $item->updatedBy->name ?? null;
                    }
                }
                $result['data'][] = [
                    'id'            => $value->id,
                    'name'          => $value->name,
                    'slug'          => $value->slug,
                    'url_img'       => $value->url_img,
                    'is_active'     => $value->is_active,
                    'subs'          => $subcategoryData,
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
