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
                    'id'            => $value->id,
                    'code'          => $value->code,
                    'user_id'       => $value->user_id,
                    'user_name'     => $value->user_name,
                    'phone'         => $value->phone,
                    'total'         => $value->total,
                    'total_formatted' => number_format($value->total).'đ',
                    'status'        => $value->status,
                    'status_code'   => $value->getStatus->code,
                    'status_name'   => $value->getStatus->name,
                    'created_at'    => $value->created_at->format('d-m-Y H:i:s'),
                    'updated_at'    => $value->updated_at->format('d-m-Y H:i:s'),
                    'created_by'    => $value->createdBy($value->created_by)->name ?? null,
                    'updated_by'    => $value->updatedBy($value->updated_by)->name ?? null,
                ];
            }
            return $result;
        }
        // $request is empty
        $result['data'] = [];
        return $result;
    }
}
