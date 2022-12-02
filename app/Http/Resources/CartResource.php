<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            $dataDetail = [];
            $totalPrice = 0;
            if(!empty($this->details)){
                foreach($this->details as $key => $detail){
                    $totalPrice += $detail->price * $detail->quantity;
                    $dataDetail[$key]['product_id'] = $detail->product_id;
                    $dataDetail[$key]['variant_id'] = $detail->variant_id;
                    $dataDetail[$key]['product_image'] = $detail->product->url_image ?? null;
                    $dataDetail[$key]['price'] = $detail->price;
                    $dataDetail[$key]['price_formatted'] = number_format($detail->price).'đ';
                    $dataDetail[$key]['quantity'] = $detail->quantity > 0 ? $detail->quantity : 0;
                }
            }

            return [
                'id'            => $this->id,
                'user_id'       => $this->user_id,
                'user_name'     => $this->user->name,
                'phone'         => $this->user->phone,
                'email'         => $this->user->email,
                'address'       => $this->address,
                'province_id'   => $this->province_id,
                'province'      => $this->province->name,
                'district_id'   => $this->district_id,
                'district'      => $this->district->name,
                'ward_id'       => $this->ward_id,
                'ward'          => $this->ward->name,
                'total'         => $totalPrice + $this->fee_ship - $this->discount ?? 0,
                'total_formatted' => number_format($totalPrice + $this->fee_ship - $this->discount ?? 0).'đ',
                'coupon_id'     => $this->coupon_id ?? null,
                'promotion_name'=> $this->coupon->promotion->name ?? null,
                'discount'      => $this->discount ?? 0,
                'discount_formatted' => number_format($this->discount ?? 0).'đ',
                'fee_ship'      => $this->fee_ship,
                'fee_ship_formatted' => number_format($this->fee_ship).'đ',
                'shipping_method_id' => $this->shipping_method_id ?? null,
                'shipping_method' => $this->shippingMethod->name ?? null,
                'payment_method_id' => $this->payment_method_id ?? null,
                'payment_method' => $this->paymentMethod->name ?? null,
                'details'       => $dataDetail,
                'created_at'    => $this->created_at->format('Y-m-d H:i:s'),
                'updated_at'    => $this->updated_at->format('Y-m-d H:i:s'),
                'created_by'    => $this->createdBy->name ?? null,
                'updated_by'    => $this->updatedBy->name ?? null,
            ];
        }

        return [];
    }
}
