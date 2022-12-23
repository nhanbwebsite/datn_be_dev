<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PromotionCollection extends ResourceCollection
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
                $couponData = [];
                if(!empty($value->coupon)){
                    foreach($value->coupon as $cp){
                        $couponData[] = [
                            'code' => $cp->code,
                            'type' => $cp->type,
                            'type_name' => $cp->type == 'D' ? 'Giảm tiền trực tiếp trên tổng hóa đơn' : ($cp->type == 'P' ? 'Giảm phần trăm tổng hóa đơn' : 'Miễn phí vận chuyển'),
                            'discount_value' => $cp->discount_value,
                            'discount_value_formatted' => $cp->type == 'D' ? number_format($cp->discount_value ?? 0).'đ' : ($cp->type == 'P' ? number_format($cp->discount_value ?? 0).'%' : 'Miễn phí vận chuyển'),
                            'max_use' => $cp->max_use,
                            'used' => count($cp->used) ?? 0,
                            'status' => $cp->status == "X" ? "Hết lượt dùng" : "Còn lượt dùng",
                        ];
                    }
                }
                $result['data'][] = [
                    'id'            => $value->id,
                    'code'          => $value->code,
                    'name'          => $value->name,
                    'expired_date'  => $value->expired_date,
                    'coupons'        => $couponData,
                    'is_active'     => $value->is_active,
                    'created_at'    => $value->created_at->format('d-m-Y H:i:s'),
                    'updated_at'    => $value->updated_at->format('d-m-Y H:i:s'),
                    'created_by'    => $value->createdBy->name ?? null,
                    'updated_by'    => $value->updatedBy->name ?? null,
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
