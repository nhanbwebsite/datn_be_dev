<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            foreach($this->details as $key => $detail){
                $dataDetails[$key]['product_id'] = $detail->product_id;
                $dataDetails[$key]['product_name'] = $detail->product->name;
                $dataDetails[$key]['variant_id'] = $detail->variant_id;
                $dataDetails[$key]['variant_name'] = $detail->variant->variant_name;
                $dataDetails[$key]['color_id'] = $detail->color_id;
                $dataDetails[$key]['color_name'] = $detail->color->name;
                $dataDetails[$key]['product_image'] = $detail->product->url_image;
                $dataDetails[$key]['price'] = $detail->price;
                $dataDetails[$key]['quantity'] = $detail->quantity;
            }

            return [
                'id'                    => $this->id,
                'code'                  => $this->code,
                'user_id'               => $this->user_id ?? null,
                'user_name'             => $this->user->name ?? null,
                'phone'                 => $this->phone,
                'email'                 => $this->email ?? null,
                'status'                => $this->status,
                'status_code'           => $this->getStatus->code,
                'status_name'           => $this->getStatus->name,
                'address'               => $this->address,
                'ward_id'               => $this->ward_id,
                'ward'                  => $this->ward->name,
                'district_id'           => $this->district_id,
                'district'              => $this->district->name,
                'province_id'           => $this->province_id,
                'province'              => $this->province->name,
                'warehouse_id'          => $this->warehouse_id ?? null,
                'warehouse'             => $this->warehouse->name ?? null,
                'total'                 => $this->total,
                'total_formatted'       => number_format($this->total).'đ',
                'discount'              => $this->discount,
                'discount_formatted'    => number_format($this->discount).'đ',
                'coupon_id'             => $this->coupon_id ?? null,
                'coupon_code'           => $this->coupon->code ?? null,
                'coupon_type'           => !empty($this->coupon->type) ? ($this->coupon->type == 'D' ? 'Giảm tiền trực tiếp trên tổng hóa đơn' : ($this->coupon->type == 'P' ? 'Giảm phần trăm tổng hóa đơn' : 'Miễn phí vận chuyển')) : null,
                'coupon_value'          => $this->coupon->value ?? null,
                'coupon_value_formatted' => !empty($this->coupon->type) ? ($this->coupon->type == 'D' ? number_format($this->coupon->value ?? 0).'đ' : ($this->coupon->type == 'P' ? number_format($this->coupon->value ?? 0).'%' : number_format($this->fee_ship ?? 0).'đ')) : null,
                'promotion_name'        => $this->coupon->promotion->name ?? null,
                'fee_ship'              => $this->fee_ship,
                'fee_ship_formatted'    => number_format($this->fee_ship).'đ',
                'payment_method_id'     => $this->payment_method_id,
                'payment_method_code'   => $this->getPaymentMethod->code,
                'payment_method_name'   => $this->getPaymentMethod->name,
                'shipping_method_id'    => $this->shipping_method_id,
                'shipping_method'       => $this->getShippingMethod->name,

                'details'               => $dataDetails,

                'created_at'            => $this->created_at->format('Y-m-d H:i:s'),
                'updated_at'            => $this->updated_at->format('Y-m-d H:i:s'),
                'created_by'            => $this->createdBy->name ?? null,
                'updated_by'            => $this->updatedBy->name ?? null,
            ];
        }

        return [];
    }
}
