<?php

namespace App\Http\Validators\Order;

use App\Http\Validators\ValidatorBase;

class OrderUpdateValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'status' => 'required|numeric',
            'payment_method_id' => 'required|numeric|exists:payment_methods,id',
            'shipping_method_id' => 'required|numeric',
            'address' => 'required|string|max:255',
            'ward_id' => 'required|numeric|exists:wards,id',
            'district_id' => 'required|numeric|exists:districts,id',
            'province_id' => 'required|numeric|exists:provinces,id',
            'email' => 'nullable|email',
            'phone' => 'required|regex:/^0[2-9]{1}[0-9]{8}$/',
        ];
    }

    protected function messages(){
        return [
            'status.required' => ':attribute không được để trống !',
            'status.numeric' => ':attribute phải là số !',
            'payment_method_id.required' => ':attribute không được để trống !',
            'payment_method_id.numeric' => ':attribute phải là số !',
            'shipping_method_id.required' => ':attribute không được để trống !',
            'shipping_method_id.numeric' => ':attribute phải là số !',
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
            'phone.required' => ':attribute không được để trống !',
            'phone.regex' => ':attribute chưa đúng định dạng ! VD: 0946636842',
        ];
    }

    protected function attributes(){
        return [
            'status' => 'Trạng thái đơn hàng',
            'address' => 'Địa chỉ',
            'ward_id' => 'Xã/Phường/Thị trấn',
            'district_id' => 'Quận/Huyện',
            'province_id' => 'Tỉnh/Thành phố',
            'phone' => 'Số điện thoại',
            'email' => 'Email',
            'payment_method_id' => 'Phương thức thanh toán',
            'shipping_method_id' => 'Hình thức vận chuyển',
        ];
    }
}

?>
