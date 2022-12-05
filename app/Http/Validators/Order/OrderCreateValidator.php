<?php

namespace App\Http\Validators\Order;

use App\Http\Validators\ValidatorBase;

class OrderCreateValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'code' => 'nullable|string|unique_deleted_at_null:orders,code',
            'phone' => 'required|regex:/^0[2-9]{1}[0-9]{8}$/',
            'fee_ship' => 'required|numeric',
            'address' => 'required|string|max:255',
            'ward_id' => 'required|numeric|exists:wards,id',
            'district_id' => 'required|numeric|exists:districts,id',
            'province_id' => 'required|numeric|exists:provinces,id',
            'email' => 'nullable|email',
            'total' => 'required|numeric',
            'user_id' => 'numeric|exists:users,id',
            'payment_method_id' => 'required|numeric|exists:payment_methods,id',
            'shipping_method_id' => 'required|numeric|exists:shipping_methods,id',
            'discount' => 'required|numeric',
            'coupon_id' => 'nullable|numeric',
            'details' => 'required',
        ];
    }

    protected function messages(){
        return [
            // 'code.required' => ':attribute không được để trống !',
            'code.string' => ':attribute phải là chuỗi !',
            'code.unique_deleted_at_null' => ':attribute đã tồn tại !',
            'phone.required' => ':attribute không được để trống !',
            'phone.regex' => ':attribute chưa đúng định dạng ! VD: 0946636842',
            'fee_ship.required' => ':attribute không được để trống !',
            'fee_ship.numeric' => ':attribute phải là số !',
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
            'total.required' => ':attribute không được để trống !',
            'total.numeric' => ':attribute phải là số !',
            'user_id.numeric' => ':attribute phải là số !',
            'user_id.exists' => ':attribute không tồn tại !',
            'payment_method_id.required' => ':attribute không được để trống !',
            'payment_method_id.numeric' => ':attribute phải là số !',
            'payment_method_id.exists' => ':attribute không tồn tại !',
            'shipping_method_id.required' => ':attribute không được để trống !',
            'shipping_method_id.numeric' => ':attribute phải là số !',
            'shipping_method_id.exists' => ':attribute không tồn tại !',
            'discount.required' => ':attribute không được để trống !',
            'discount.numeric' => ':attribute chưa đúng !',
            'fee_ship.required' => ':attribute không được để trống !',
            'fee_ship.numeric' => ':attribute chưa đúng !',
            'coupon_id.numeric' => ':attribute phải là số !',
            'details.required' => ':attribute không được để trống !',
        ];
    }

    protected function attributes(){
        return [
            'code' => 'Mã đơn hàng',
            'phone' => 'Số điện thoại',
            'email' => 'Email',
            'fee_ship' => 'Phí vận chuyển',
            'address' => 'Địa chỉ',
            'ward_id' => 'Xã/Phường/Thị trấn',
            'district_id' => 'Quận/Huyện',
            'province_id' => 'Tỉnh/Thành phố',
            'email' => 'Email',
            'total' => 'Tổng tiền',
            'user_id' => 'Mã người dùng',
            'payment_method_id' => 'Phương thức thanh toán',
            'shipping_method_id' => 'Hình thức vận chuyển',
            'discount' => 'Giảm giá',
            'fee_ship' => 'Phí vận chuyển',
            'coupon_id' => 'Mã giảm giá',
            'details' => 'Chi tiết đơn hàng',
        ];
    }
}

?>
