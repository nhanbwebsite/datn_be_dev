<?php

namespace App\Http\Validators\AddressNote;

use App\Http\Validators\ValidatorBase;

class AddressNoteUpdateValidator extends ValidatorBase{
    protected function rules(){
        return [
            'phone' => 'string|min:10|regex:/^0[2-9]{1}[0-9]{8}$/',
            'email' => 'string|email|max:255',
            'address' => 'required|string|max:255',
            'province_id' => 'required|numeric|exists:provinces,id',
            'district_id' => 'required|numeric|exists:districts,id',
            'ward_id' => 'required|numeric|exists:wards,id',
            'is_default' => 'numeric',
            'is_active' => 'numeric',
        ];
    }

    protected function messages(){
        return [
            'phone.string' => ':attribute chưa đúng !',
            'phone.min' => ':attribute phải đủ 10 ký tự !',
            'phone.regex' => ':attribute chưa đúng định dạng ! VD: 0946636842',
            'email.string' => ':attribute phải là chuỗi !',
            'email.email' => ':attribute chưa đúng định dạng ! VD: duynh123@gmail.com',
            'email.max' => ':attribute tối đa 255 ký tự !',
            'address.max' => ':attribute tối đa 255 ký tự !',
            'address.required' => ':attribute không được để trống !',
            'address.string' => ':attribute phải là chuỗi !',
            'province_id.required' => ':attribute không được để trống !',
            'province_id.numeric' => ':attribute phải là số !',
            'province_id.exists' => ':attribute không tồn tại',
            'district_id.required' => ':attribute không được để trống !',
            'district_id.numeric' => ':attribute phải là số !',
            'district_id.exists' => ':attribute không tồn tại',
            'ward_id.required' => ':attribute không được để trống !',
            'ward_id.numeric' => ':attribute phải là số !',
            'ward_id.exists' => ':attribute không tồn tại',
            'is_default.numeric' => ':attribute phải là số !',
            'is_active.numeric' => ':attribute phải là số !',
        ];
    }

    protected function attributes(){
        return [
            'user_id' => 'Mã người dùng',
            'phone' => 'Số điện thoại',
            'email' => 'Email',
            'address' => 'Địa chỉ',
            'ward_id' => 'Xã/Phường/Thị trấn',
            'district_id' => 'Quận/Huyện',
            'province_id' => 'Tỉnh/Thành phố',
            'is_default' => 'Mặc định',
            'is_active' => 'Kích hoạt',
        ];
    }
}

?>
