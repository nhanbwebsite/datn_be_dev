<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CouponCollection extends ResourceCollection
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
                $result['data'][] = [
                    'id'            => $value->id,
                    'code'          => $value->code,
                    'type'          => $value->type,
                    'type_name'     => $value->type == 'D' ? 'Giảm tiền trực tiếp trên tổng hóa đơn' : ($value->type == 'P' ? 'Giảm phần trăm tổng hóa đơn' : 'Miễn phí vận chuyển'),
                    'discount_value' => $value->discount_value ?? null,
                    'discount_value_formatted' => $value->type == 'D' ? number_format($value->discount_value ?? 0).'đ' : ($value->type == 'P' ? number_format($value->discount_value ?? 0).'%' : 'Miễn phí vận chuyển'),
                    'max_use'       => $value->max_use ?? null,
                    'used'          => count($value->used)?? 0,
                    'status'        => $value->status,
                    'status_name'   => $value->status == 'X' ? 'Hết lượt dùng' : 'Còn lượt dùng',
                    'promotion_id'  => $value->promotion_id,
                    'promotion_name'  => $value->promotion->name ?? null,
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
