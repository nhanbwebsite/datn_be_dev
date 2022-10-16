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
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'ward' => $this->ward->name,
                'district' => $this->district->name,
                'province' => $this->province->name,
                'role' => $this->role->name,
                'store' => $this->store->store_name ?? null,
                'is_active' => $this->is_active,
                'created_at' => $this->created_at->format('d-m-Y H:i:s'),
                'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
                'created_by' => $this->createdBy($this->created_by)->name ?? null,
                'updated_by' => $this->updatedBy($this->updated_by)->name ?? null,
            ];
        }

        return [];
    }
}
