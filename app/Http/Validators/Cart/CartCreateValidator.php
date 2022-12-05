<?php

namespace App\Http\Validators\Cart;

use App\Http\Validators\ValidatorBase;

class CartCreateValidator extends ValidatorBase
{

    protected function rules(){
        return [
            'user_id' => 'exists:users,id',
            'address' => 'string|max:255',
            'ward_id' => 'exists:wards,id',
            'district_id' => 'exists:districts,id',
            'province_id' => 'exists:provinces,id',
            'email' => 'nullable|email',
            'phone' => 'regex:/^0[2-9]{1}[0-9]{8}$/',
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'required|exists:variants,id',
            'color_id' => 'required|exists:colors,id',
            'quantity' => 'required|numeric|min:1',
        ];
    }

    protected function messages(){
        return [
            'user_id.exists' => ':attribute không tồn tại !',
            'address.string' => ':attribute phải là chuỗi !',
            'address.max' => ':attribute tối đa 255 ký tự!',
            'ward_id.exists' => ':attribute không tồn tại !',
            'district_id.exists' => ':attribute không tồn tại !',
            'province_id.exists' => ':attribute không tồn tại !',
            'email.email' => ':attribute chưa đúng định dạng ! VD: duynhpc02604@gmail.com',
            'phone.regex' => ':attribute chưa đúng định dạng ! VD: 0946636842',
            'product_id.required' => ':attribute không được để trống !',
            'product_id.exists' => ':attribute không tồn tại !',
            'variant_id.required' => ':attribute không được để trống !',
            'variant_id.exists' => ':attribute không tồn tại !',
            'color_id.required' => ':attribute không được để trống !',
            'color_id.exists' => ':attribute không tồn tại !',
            'quantity.required' => ':attribute không được để trống !',
            'quantity.numeric' => ':attribute phải là số !',
            'quantity.min' => ':attribute tối thiểu là 1 !',
        ];
    }

    protected function attributes(){
        return [
            'user_id' => 'ID người dùng',
            'address' => 'Địa chỉ',
            'ward_id' => 'Xã/Phường/Thị trấn',
            'district_id' => 'Quận/Huyện',
            'province_id' => 'Tỉnh/Thành phố',
            'phone' => 'Số điện thoại',
            'email' => 'Email',
            'product_id' => 'ID sản phẩm',
            'variant_id' => 'ID biến thể sản phẩm',
            'quantity' => 'Số lượng',
            'color_id' => 'Màu sắc',
        ];
    }
}

?>
