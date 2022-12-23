<?php

namespace App\Http\Resources;

use App\Models\ProductVariantDetail;
use App\Models\ProductVariantDetailById;
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
                    $variant = ProductVariantDetail::where('variant_id', $detail->variant_id)->where('product_id', $detail->product_id)->first();
                    $color = ProductVariantDetailById::where('pro_variant_id', $variant->pro_variant->pro_variant_id)->where('color_id', $detail->color_id)->first();

                    $totalPrice += $detail->price * $detail->quantity;
                    $dataDetail[$key]['product_id'] = $detail->product_id;
                    $dataDetail[$key]['product_name'] = $detail->product->name;
                    $dataDetail[$key]['variant_id'] = $detail->variant_id;
                    $dataDetail[$key]['variant_name'] = $detail->variant->variant_product->variant_name;
                    $dataDetail[$key]['color_id'] = $detail->color_id;
                    $dataDetail[$key]['color_name'] = $color->color->name;
                    $dataDetail[$key]['product_image'] = $detail->product->url_image ?? null;
                    $dataDetail[$key]['original_price'] = $color->price;
                    $dataDetail[$key]['original_price_formatted'] = number_format($color->price).'đ';
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
                'total'         => $totalPrice,
                'total_formatted' => number_format($totalPrice).'đ',
                'coupon_id'     => $this->coupon_id ?? null,
                'coupon_code'     => $this->coupon->code ?? null,
                'discount'      => $this->discount ?? 0,
                'discount_formatted' => number_format($this->discount ?? 0).'đ',
                'fee_ship'      => $this->fee_ship,
                'fee_ship_formatted' => number_format($this->fee_ship).'đ',
                'shipping_method_id' => $this->shipping_method_id ?? null,
                'shipping_method' => $this->shippingMethod->name ?? null,
                'payment_method_id' => $this->payment_method_id ?? null,
                'payment_method' => $this->paymentMethod->name ?? null,
                'details'       => $dataDetail,
                'created_at'    => $this->created_at->format('d-m-Y H:i:s'),
                'updated_at'    => $this->updated_at->format('d-m-Y H:i:s'),
                'created_by'    => $this->createdBy->name ?? null,
                'updated_by'    => $this->updatedBy->name ?? null,
            ];
        }

        return [];
    }
}
