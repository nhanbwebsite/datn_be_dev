<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WishlistCollection extends ResourceCollection
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
                $result['data'][] = [
                    'id' => $value->id,
                    'user_id' => $value->user_id,
                    'user_name' => $value->user->name,
                    'product_id' => $value->product_id, // chưa có
                    'product_name' => $value->product->name, // chưa có
                    'product_price' => $value->product->price, // chưa có
                    'product_price_sale' => $value->product->price_sale, // chưa có
                    'product_image' => $value->product->image, // chưa có, sau này file url
                    'created_at' => $value->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $value->updated_at->format('Y-m-d H:i:s'),
                    'created_by' => $value->createdBy->name ?? null,
                    'updated_by' => $value->updatedBy->name ?? null,
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
