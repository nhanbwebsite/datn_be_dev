<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressNoteResource extends JsonResource
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
                'user_id'       => $this->user_id,
                'user_name'     => $this->user->name,
                'phone'         => $this->phone,
                'email'         => $this->email,
                'address'       => $this->address,
                'province_id'   => $this->province_id,
                'province'      => $this->province->name,
                'district_id'   => $this->district_id,
                'district'      => $this->district->name,
                'ward_id'       => $this->ward_id,
                'ward'          => $this->ward->name,
                'is_default'    => $this->is_default,
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
