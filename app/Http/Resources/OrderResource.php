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
                $dataDetails[$key]['product_image'] = $detail->product->image;
                $dataDetails[$key]['price'] = $detail->price;
                $dataDetails[$key]['quantity'] = $detail->quantity;
            }

            return [
                'id'                    => $this->id,
                'code'                  => $this->code,
                'user_id'               => $this->addressNote->user_id,
                'user_name'             => $this->addressNote->user->name,
                'phone'                 => $this->addressNote->user->phone,
                'email'                 => $this->addressNote->user->email,
                'status'                => $this->status,
                'status_code'           => $this->getStatus->code,
                'status_name'           => $this->getStatus->name,
                'address_note_id'       => $this->address_note_id,
                'address'               => $this->addressNote->address,
                'ward_id'               => $this->addressNote->ward_id,
                'ward'                  => $this->addressNote->ward->name,
                'district_id'           => $this->addressNote->district_id,
                'district'              => $this->addressNote->district->name,
                'province_id'           => $this->addressNote->province_id,
                'province'              => $this->addressNote->province->name,
                'total'                 => $this->total,
                'total_formatted'       => number_format($this->total).'đ',
                'discount'              => $this->discount,
                'discount_formatted'    => number_format($this->discount).'đ',
                'fee_ship'              => $this->fee_ship,
                'fee_ship_formatted'    => number_format($this->fee_ship).'đ',
                'payment_method_id'     => $this->payment_method_id,
                // 'payment_method_code'   => $this->paymentMethod->code,
                // 'payment_method_name'   => $this->paymentMethod->name,
                'shipping_method_id'    => $this->shipping_method_id,
                // 'shipping_method'       => $this->shipping_method->name,

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
