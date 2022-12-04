<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class productImportSlipResource extends JsonResource
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
                'code'          => $this->code,
                'name'          => $this->name,
                'warehouse_id'  => $this->warehouse_id,
                'warehouse'     => $this->warehouse->name,
                'status'        => $this->status,
                'status_name'   => $this->status == "X" ? "Chưa nhập" : "Đã nhập",
                'note'          => $this->note ?? null,
                'details'       => $this->details,
                'created_at'    => $this->created_at->format('Y-m-d H:i:s'),
                'updated_at'    => $this->updated_at->format('Y-m-d H:i:s'),
                'created_by'    => $this->createdBy->name ?? null,
                'updated_by'    => $this->updatedBy->name ?? null,
            ];
        }
        return [];
    }
}
