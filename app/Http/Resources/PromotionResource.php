<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PromotionResource extends JsonResource
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
            $couponData = [];
            if(!empty($this->coupon)){
                foreach($this->coupon as $cp){
                    $couponData[] = [
                        'code' => $cp->code,
                        'type' => $cp->type,
                        'type_name' => $cp->type == 'D' ? 'Giảm tiền trực tiếp trên tổng hóa đơn' : ($cp->type == 'P' ? 'Giảm phần trăm tổng hóa đơn' : 'Miễn phí vận chuyển'),
                        'discount_value' => $cp->discount_value,
                        'discount_value_formatted' => $cp->type == 'D' ? number_format($cp->discount_value ?? 0).'đ' : ($cp->type == 'P' ? number_format($cp->discount_value ?? 0).'%' : 'Miễn phí vận chuyển'),
                        'max_use' => $cp->max_use,
                        'used' => count($cp->used) ?? 0,
                        'status' => $cp->status == "X" ? "Hết" : "Còn",
                    ];
                }
            }

            return [
                'id'            => $this->id,
                'code'          => $this->code,
                'name'          => $this->name,
                'expired_date'  => $this->expired_date,
                'coupons'        => $couponData,
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
