<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NewCustomerStatistic extends ResourceCollection
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
                    'name'          => $value->name,
                    'phone'         => $value->phone,
                    'province'      => $value->province->name,
                    'created_at'    => $value->created_at->format('d-m-Y H:i:s'),
                    'is_active' => $value->is_active,
                ];
            }
            return $result;
        }
        // $request is empty
        $result['data'] = [];
        return $result;
    }
}
