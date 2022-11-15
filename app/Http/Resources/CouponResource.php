<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
                'type'          => $this->type == 'D' ? 'Giảm tiền trực tiếp trên tổng hóa đơn' : ($this->type == 'P' ? 'Giảm phần trăm tổng hóa đơn' : 'Miễn phí vận chuyển'),
                'discount_value' => $this->discount_value ?? null,
                'discount_value_formatted' => $this->type == 'D' ? number_format($this->discount_value ?? 0).'đ' : ($this->type == 'P' ? number_format($this->discount_value ?? 0).'%' : 'Miễn phí vận chuyển'),
                'max_use'       => $this->max_use ?? null,
                'used'          => count($this->used)?? 0,
                'status'        => $this->status == 'X' ? 'Hết lượt dùng' : 'Còn lượt dùng',
                'promotion_id'  => $this->promotion_id,
                'promotion_name'  => $this->promotion->name ?? null,
                'is_active'     => $this->is_active,
                'created_at'    => $this->created_at->format('d-m-Y H:i:s'),
                'updated_at'    => $this->updated_at->format('d-m-Y H:i:s'),
                'created_by'    => $this->createdBy->name ?? null,
                'updated_by'    => $this->updatedBy->name ?? null,
            ];
        }

        return [];
    }
}
