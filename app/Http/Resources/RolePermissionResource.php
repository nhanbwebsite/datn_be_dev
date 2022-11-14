<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RolePermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if(!empty($request)){
            return [
                'id'                => $this->id,
                'role_id'           => $this->role_id,
                'role'              => $this->role->name,
                'role_code'         => $this->role->code,
                'permission_id'     => $this->permission_id,
                'permission'        => $this->permission->name,
                'permission_code'   => $this->permission->code,
                'is_active'         => $this->is_active,
                'created_at'        => $this->created_at->format('d-m-Y H:i:s'),
                'updated_at'        => $this->updated_at->format('d-m-Y H:i:s'),
                'created_by'        => $this->createdBy->name ?? null,
                'updated_by'        => $this->updatedBy->name ?? null,
            ];
        }

        return [];
    }
}
