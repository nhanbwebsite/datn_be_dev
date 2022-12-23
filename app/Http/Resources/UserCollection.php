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
        if(!$this->collection->isEmpty()){
            // $request not empty
            foreach($this->collection as $value){
                $result['data'][] = [
                    'id'            => $value->id,
                    'name'          => $value->name,
                    'email'         => $value->email,
                    'phone'         => $value->phone,
                    'address'       => !empty($value->address) ? $value->address : null,
                    'ward_id'       => $value->ward_id,
                    'ward'          => $value->ward->name,
                    'district_id'   => $value->district_id,
                    'district'      => $value->district->name,
                    'province_id'   => $value->province_id,
                    'province'      => $value->province->name,
                    'role_id'       => $value->role_id,
                    'role'          => !empty($value->role->name) ? $value->role->name : null,
                    'store_id'      => $value->store_id,
                    'store'         => !empty($value->store->store_name) ? $value->store->store_name : null,
                    'is_active'     => $value->is_active,
                    'created_at'    => $value->created_at->format('d-m-Y H:i:s'),
                    'updated_at'    => $value->updated_at->format('d-m-Y H:i:s'),
                    'created_by'    => $value->createdBy($value->created_by)->name ?? null,
                    'updated_by'    => $value->updatedBy($value->updated_by)->name ?? null,
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
