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
                    $dataDetails[$key]['product_name'] = $detail->product->name;
                    $dataDetails[$key]['product_image'] = $detail->product->image;
                    $dataDetails[$key]['price'] = $detail->price;
                    $dataDetails[$key]['quantity'] = $detail->quantity;
                }

                $result['data'][] = [
                    'id'                    => $value->id,
                    'code'                  => $value->code,
                    'user_id'               => $value->user_id ?? null,
                    'user_name'             => $value->user->name ?? null,
                    'phone'                 => $value->phone,
                    'email'                 => $value->email ?? null,
                    'status'                => $value->status,
                    'status_code'           => $value->getStatus->code,
                    'status_name'           => $value->getStatus->name,
                    'type'                  => $value->type,
                    'type_name'             => $value->type == 'L' ? 'LOGIN' : 'NOT LOGIN',
                    'address_note_id'       => $value->address_note_id,
                    'address'               => $value->address,
                    'ward_id'               => $value->ward_id,
                    'ward'                  => $value->ward->name,
                    'district_id'           => $value->district_id,
                    'district'              => $value->district->name,
                    'province_id'           => $value->province_id,
                    'province'              => $value->province->name,
                    'total'                 => $value->total,
                    'total_formatted'       => number_format($value->total).'đ',
                    'discount'              => $value->discount,
                    'discount_formatted'    => number_format($value->discount).'đ',
                    'fee_ship'              => $value->fee_ship,
                    'fee_ship_formatted'    => number_format($value->fee_ship).'đ',
                    'payment_method_id'     => $value->payment_method_id,
                    'payment_method_code'   => $value->getPaymentMetyhod->code,
                    'payment_method_name'   => $value->getPaymentMetyhod->name,
                    'shipping_method_id'    => $value->shipping_method_id,
                    // 'shipping_method'       => $value->shipping_method->name,

                    'details'               => $dataDetails,

                    'created_at'            => $value->created_at->format('Y-m-d H:i:s'),
                    'updated_at'            => $value->updated_at->format('Y-m-d H:i:s'),
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
