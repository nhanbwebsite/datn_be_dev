<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
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
                dd($value);
                $result['data'][] = [
                    'id'            => $value->id,
                    'code'          => $value->code,
                    'name'          => $value->name,
                    'slug'          => $value->slug,
                    'meta_title'    => $value->meta_title,
                    'meta_keywords' => $value->meta_keywords,
                    'meta_description' => $value->meta_description,
                    'description'   => $value->description,
                    'url_image'     => $value->url_image,
                    'price'         => $value->price,
                    'price_formatted' => number_format($value->price ?? 0).'đ',
                    'discount'      => $value->discount,
                    'discount_formatted' => number_format($value->discount ?? 0).'đ',
                    'specification_infomation' => $value->specification_infomation,
                    'brand_id' => $value->brand_id,
                    'brand_name' => $value->brand->brand_name,
                    'subcategory_id' => $value->subcategory_id,
                    'subcategory_name' => $value->subcategory->name,
                    'variants' => $value->variants,
                    'variantsDetailsByProduct' => $this->variantsDetailsByProduct ?? null,
                    'category' => $value->subcategory->category_id,
                    'category_name' => $value->subcategory->category->name,
                    'is_active'     => $value->is_active,
                    'created_at'    => $value->created_at->format('Y-m-d H:i:s'),
                    'updated_at'    => $value->updated_at->format('Y-m-d H:i:s'),
                    'created_by'    => $value->createdBy->name ?? null,
                    'updated_by'    => $value->updatedBy->name ?? null,
                ];
            }
            $result['paginator'] = [
                'currentPage' => !empty($this->currentPage()) ? $this->currentPage() : null,
                'totalPages' => $this->lastPage(),
                'perPage' => $this->perPage(),
                'count' => $this->count(),
                'total' => $this->total(),
                'nextPageUrl' => $this->nextPageUrl(),
                'prevPageUrl' => $this->previousPageUrl(),
            ];
             dd($this->collection);
            return $result;
        }

        // $request is empty
        $result['data'] = [];
        $result['paginator'] = [];
        return $result;
    }

}
