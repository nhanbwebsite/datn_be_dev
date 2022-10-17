<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
class UserResource extends JsonResource
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
                'name'          => $this->name,
                'email'         => $this->email,
                'phone'         => $this->phone,
                'address'       => $this->address,
                'ward_id'       => $this->ward_id,
                'ward'          => $this->ward->name,
                'district_id'   => $this->district_id,
                'district'      => $this->district->name,
                'province_id'   => $this->province_id,
                'province'      => $this->province->name,
                'role_id'       => $this->role_id,
                'role'          => $this->role->name,
                'store_id'      => $this->store_id,
                'store'         => $this->store->store_name ?? null,
                'is_active'     => $this->is_active,
                'created_at'    => $this->created_at->format('d-m-Y H:i:s'),
                'updated_at'    => $this->updated_at->format('d-m-Y H:i:s'),
                'created_by'    => $this->createdBy($this->created_by)->name ?? null,
                'updated_by'    => $this->updatedBy($this->updated_by)->name ?? null,
            ];
        }

        return [];
    }
}
