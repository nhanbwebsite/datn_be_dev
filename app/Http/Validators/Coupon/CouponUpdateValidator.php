<?php

namespace App\Http\Validators\Coupon;

use App\Http\Validators\ValidatorBase;

class CouponUpdateValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'type' => 'required|regex:/^[D,P,F]$/',
            'discount_value' => 'required|numeric',
            'max_use' => 'required|numeric',
            'status' => 'required|regex:/^[X,O]$/',
            'promotion_id' => 'required|numeric|exists:promotions,id',
            'is_active' => 'numeric',
        ];
    }

    protected function messages(){
        return [
            'type.required' => ':attribute không được để trống !',
            'type.regex' => ':attribute chưa đúng định dạng ! (D, P hoặc F)',
            'discount_value.required' => ':attribute không được để trống !',
            'discount_value.numeric' => ':attribute phải là số !',
            'max_use.required' => ':attribute không được để trống !',
            'max_use.numeric' => ':attribute phải là số !',
            'status.required' => ':attribute không được để trống !',
            'status.regex' => ':attribute chưa đúng định dạng ! (X hoặc O)',
            'promotion_id.required' => ':attribute không được để trống !',
            'promotion_id.numeric' => ':attribute phải là số !',
            'promotion_id.exists' => ':attribute không tồn tại !',
            'is_active' => ':attribute phải là số !',
        ];
    }

    protected function attributes(){
        return [
            'type' => 'Loại mã giảm giá',
            'discount_value' => 'Giá trị giảm',
            'max_use' => 'Lượt dùng',
            'status' => 'Trạng thái mã giảm giá',
            'promotion_id' => 'Mã chương trình khuyến mãi',
            'is_active' => 'Trạng thái kích hoạt',
        ];
    }
}

?>
