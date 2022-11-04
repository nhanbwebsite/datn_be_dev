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
            if(!empty($request->details)){
                foreach($request->details as $key => $detail){
                    $dataDetail[$key]['product_id'] = $detail->product_id;
                    $dataDetail[$key]['product_image'] = $detail->product->url_image ?? null;
                    $dataDetail[$key]['price'] = $detail->price;
                    $dataDetail[$key]['quantity'] = $detail->quantity;
                }
            }

            return [
                'id'            => $this->id,
                'user_id'       => $this->user_id,
                'user_name'     => $this->user->name,
                'phone'         => $this->user->phone,
                'email'         => $this->user->email,
                'address_note_id' => $this->address_note_id,
                'address'       => $this->addressNote->address,
                'province_id'   => $this->addressNote->province_id,
                'province'      => $this->addressNote->province->name,
                'district_id'   => $this->addressNote->district_id,
                'district'      => $this->addressNote->district->name,
                'ward_id'       => $this->addressNote->ward_id,
                'ward'          => $this->addressNote->ward->name,
                'coupon_id'     => $this->coupon_id,
                'promotion_id'  => $this->promotion_id,
                'fee_ship'      => $this->fee_ship,
                'fee_ship_formatted' => number_format($this->fee_ship),
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
