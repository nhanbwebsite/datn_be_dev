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
            dd($this->collection);
            // $request not empty
            foreach($this->collection as $value){
                foreach($value->subs as $key => $item){
                    $subcategoryData[$key]['id'] = $item->id;
                    $subcategoryData[$key]['category_id'] = $value->id;
                    $subcategoryData[$key]['name'] = $item->name;
                    $subcategoryData[$key]['slug'] = $item->slug;
                    $subcategoryData[$key]['url_img'] = $item->url_img ?? null;
                    $subcategoryData[$key]['is_active'] = $item->is_active;
                    $subcategoryData[$key]['created_at'] = $item->created_at->format('Y-m-d H:i:s');
                    $subcategoryData[$key]['updated_at'] = $item->updated_at->format('Y-m-d H:i:s');
                    $subcategoryData[$key]['created_by'] = $item->created_by;
                    $subcategoryData[$key]['updated_by'] = $item->updated_by;
                }

                $result['data'][] = [
                    'id'            => $value->id,
                    'name'          => $value->name,
                    'slug'          => $value->slug,
                    'url_img'       => $value->url_img,
                    'is_active'     => $value->is_active,
                    'subs'          => $subcategoryData ?? null,
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
