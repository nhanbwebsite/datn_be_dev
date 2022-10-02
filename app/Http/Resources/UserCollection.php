<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
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
        if(!empty($request)){
            // $request not empty
            foreach($this->collection as $value){
                $result['data'][] = [
                    'name' => $value->name,
                    'email' => $value->email,
                    'phone' => $value->phone,
                    'address' => !empty($value->address) ? $value->address : null,
                    'ward' => $value->ward->name,
                    'district' => $value->district->name,
                    'province' => $value->province->name,
                    'role' => !empty($value->role->name) ? $value->role->name : null,
                    'is_active' => $value->is_active,
                    'deleted' => $value->deleted,
                    'created_at' => $value->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $value->updated_at->format('Y-m-d H:i:s'),
                    'created_by' => $value->created_by,
                    'updated_by' => $value->updated_by,
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
        return [];
    }
}