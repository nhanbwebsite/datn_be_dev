<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ClientOrderStatusCollection extends ResourceCollection
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
                    'id'            => $value->id,
                    'name'          => $value->name,
                    'code'          => $value->code,
                    'sort_level'    => $value->sort_level,
                    'is_active'     => $value->is_active,
                    'created_at'    => $value->created_at->format('d-m-Y H:i:s'),
                    'updated_at'    => $value->updated_at->format('d-m-Y H:i:s'),
                    'created_by'    => $value->createdBy->name ?? null,
                    'updated_by'    => $value->updatedBy->name ?? null,
                ];
            }
            return $result;
        }

        // $request is empty
        $result['data'] = [];
        return $result;
    }
}
