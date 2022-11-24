<?php

namespace App\Http\Validators\Cart;

use App\Http\Validators\ValidatorBase;

class CartCreateValidator extends ValidatorBase
{

    protected function rules(){
        return [
            'phone' => 'required|regex:/^0[2-9]{1}[0-9]{8}$/',
            'email' => 'nullable|email',
            'address' => 'required|string|max:255',
            'ward_id' => 'required|numeric|exists:wards,id',
            'district_id' => 'required|numeric|exists:districts,id',
            'province_id' => 'required|numeric|exists:provinces,id',
            'coupon_id' => 'nullable|numeric',
            'discount' => 'required|numeric',
            'fee_ship' => 'required|numeric',
            'details' => 'required',
        ];
    }

    protected function messages(){
        return [
            'phone.required' => ':attribute không được để trống !',
            'phone.regex' => ':attribute không được để trống !',
            'email.email' => ':attribute chưa đúng định dạng email !',
            'address.required' => ':attribute không được để trống !',
            'address.string' => ':attribute chưa đúng !',
            'address.max' => ':attribute tối đa 255 ký tự !',
            'ward_id.required' => ':attribute không được để trống !',
            'ward_id.numeric' => ':attribute phải là số !',
            'ward_id.exists' => ':attribute không tồn tại !',
            'district_id.required' => ':attribute không được để trống !',
            'district_id.numeric' => ':attribute phải là số !',
            'district_id.exists' => ':attribute không tồn tại !',
            'province_id.required' => ':attribute không được để trống !',
            'province_id.numeric' => ':attribute phải là số !',
            'province_id.exists' => ':attribute không tồn tại !',
            'coupon_id.numeric' => ':attribute phải là số !',
            'discount.required' => ':attribute không được để trống !',
            'discount.numeric' => ':attribute phải là số !',
            'fee_ship.required' => ':attribute không được để trống !',
            'fee_ship.numeric' => ':attribute phải là số !',
            'details.required' => ':attribute không được để trống !',
        ];
    }

    protected function attributes(){
        return [
            'phone' => 'Số điện thoại',
            'email' => 'Email',
            'address' => 'Địa chỉ',
            'ward_id' => 'Xã/Phường/Thị trấn',
            'district_id' => 'Quận/Huyện',
            'province_id' => 'Tỉnh/Thành phố',
            'coupon_id' => 'Mã giảm giá',
            'discount' => 'Tiền giảm',
            'fee_ship' => 'Phí ship',
            'details' => 'Chi tiết',
        ];
    }
}

?>
