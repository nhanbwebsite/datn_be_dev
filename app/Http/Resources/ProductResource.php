<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            return [
                'id'            => $this->id,
                'code'          => $this->code,
                'name'          => $this->name,
                'slug'          => $this->slug,
                'meta_title'    => $this->meta_title,
                'meta_keywords' => $this->meta_keywords,
                'meta_description' => $this->meta_description,
                'description'   => $this->description,
                'url_image'     => $this->url_image,
                // 'price'         => $this->price,
                // 'price_formatted' => number_format($this->price ?? 0).'đ',
                // 'discount'      => $this->discount,
                // 'discount_formatted' => number_format($this->discount ?? 0).'đ',
                'specification_infomation' => $this->specification_infomation,
                'brand_id'      => $this->brand_id,
                'brand_name'    => $this->brand->brand_name,
                'subcategory_id' => $this->subcategory_id,
                'subcategory_name' => $this->subcategory->name,
                'category'      => $this->subcategory->category_id,
                'category_name' => $this->subcategory->category->name,
                'variants'      => $this->variants ?? null,
                'variantsDetailsByProduct' => $this->variantsDetailsByProduct ?? null,
                'dataVariants' => $this->dataVariants ?? null,
                'is_active'     => $this->is_active,
                'created_at'    => $this->created_at->format('Y-m-d H:i:s'),
                'updated_at'    => $this->updated_at->format('Y-m-d H:i:s'),
                'created_by'    => $this->createdBy->name ?? null,
                'updated_by'    => $this->updatedBy->name ?? null,
            ];
        }

        return [];
    }
}
