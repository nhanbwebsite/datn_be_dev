<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NewOrderStatistic extends ResourceCollection
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
                    'code'          => $value->code,
                    'phone'         => $value->phone,
                    'total_formatted' => number_format($value->total).'đ',
                    'created_at'    => $value->created_at->format('d-m-Y H:i:s'),
                    'status_name'   => $value->getStatus->name,
                ];
            }
            return $result;
        }
        // $request is empty
        $result['data'] = [];
        return $result;
    }
}
