<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        if(!$this->collection->isEmpty()){
            // $request not empty
            foreach($this->collection as $value){

                foreach($value->details as $key => $detail){

                    $dataDetails[$key]['product_id'] = $detail->product_id;
                    $dataDetails[$key]['product_name'] = $detail->product->name ?? null;
                    $dataDetails[$key]['variant_id'] = $detail->variant_id;
                    $dataDetails[$key]['variant_name'] = $detail->variant->variant_name ?? null;
                    $dataDetails[$key]['color_id'] = $detail->color_id;
                    $dataDetails[$key]['color_name'] = $detail->color->name ?? null;
                    $dataDetails[$key]['product_image'] = $detail->product->url_image ?? null;
                    $dataDetails[$key]['price'] = $detail->price;
                    $dataDetails[$key]['quantity'] = $detail->quantity;
                }

                $result['data'][] = [
                    'id'                    => $value->id,
                    'code'                  => $value->code,
                    'user_id'               => $value->user_id ?? null,
                    'user_name'             => $value->user_name ?? null,
                    'phone'                 => $value->phone,
                    'email'                 => $value->email ?? null,
                    'status'                => $value->status,
                    'status_code'           => $value->getStatus->code,
                    'status_name'           => $value->getStatus->name,
                    'address'               => $value->address,
                    'ward_id'               => $value->ward_id,
                    'ward'                  => $value->ward->name,
                    'district_id'           => $value->district_id,
                    'district'              => $value->district->name,
                    'province_id'           => $value->province_id,
                    'province'              => $value->province->name,
                    'warehouse_id'          => $value->warehouse_id ?? null,
                    'warehouse'             => $value->warehouse->name ?? null,
                    'total'                 => $value->total,
                    'total_formatted'       => number_format($value->total).'đ',
                    'discount'              => $value->discount,
                    'discount_formatted'    => number_format($value->discount).'đ',
                    'coupon_id'             => $value->coupon_id ?? null,
                    'coupon_code'           => $value->coupon->code ?? null,
                    'coupon_type'           => !empty($value->coupon->type) && $value->coupon->type == 'D' ? 'Giảm tiền trực tiếp trên tổng hóa đơn' : (!empty($value->coupon->type) && $value->coupon->type == 'P' ? 'Giảm phần trăm tổng hóa đơn' : (!empty($value->coupon->type) && $value->coupon->type == 'F' ? 'Miễn phí vận chuyển' : null)),
                    'coupon_value'          => $value->coupon->value ?? null,
                    'coupon_value_formatted' => !empty($value->coupon->type) && $value->coupon->type == 'D' ? number_format($value->coupon->value ?? 0).'đ' : (!empty($value->coupon->type) && $value->coupon->type == 'P' ? number_format($value->coupon->value ?? 0).'%' : (!empty($value->coupon->type) && $value->coupon->type == 'D' ? number_format($value->fee_ship ?? 0).'đ': null)),
                    'promotion_name'        => $value->coupon->promotion->name ?? null,
                    'fee_ship'              => $value->fee_ship,
                    'fee_ship_formatted'    => number_format($value->fee_ship).'đ',
                    'payment_method_id'     => $value->payment_method_id,
                    'payment_method_code'   => $value->getPaymentMethod->code,
                    'payment_method_name'   => $value->getPaymentMethod->name,
                    'shipping_method_id'    => $value->shipping_method_id,
                    'shipping_method'       => $value->getShippingMethod->name,

                    'details'               => $dataDetails,

                    'created_at'            => $value->created_at->format('d-m-Y H:i:s'),
                    'updated_at'            => $value->updated_at->format('d-m-Y H:i:s'),
                    'created_by'            => $value->createdBy->name ?? null,
                    'updated_by'            => $value->updatedBy->name ?? null,
                ];
            }
            $result['paginator'] = [
                'currentPage' => $this->currentPage(),
                'totalPages' => $this->lastPage(),
                'perPage' => $this->perPage(),
                'count' => $this->count(),
                'total' => $this->total(),
                'nextPageUrl' => $this->nextPageUrl(),
                'prevPageUrl' => $this->previousPageUrl(),
            ];
            return $result;
        }

        // $request is empty
        $result['data'] = [];
        $result['paginator'] = [];
        return $result;
    }
}
