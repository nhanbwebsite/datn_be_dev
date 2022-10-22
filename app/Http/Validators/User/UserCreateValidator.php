<?php

namespace App\Http\Validators\User;

use App\Http\Validators\ValidatorBase;

class UserCreateValidator extends ValidatorBase{
    protected function rules(){
        return [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'role_id'   => 'nullable|numeric|exists:roles,id',
            'store_id'   => 'nullable|numeric|exists:stores,id',
            'ward_id' => 'required|exists:wards,id',
            'district_id' => 'required|exists:districts,id',
            'province_id' => 'required|exists:provinces,id',
            'phone' => 'required|string|min:10|regex:/^0[2-9]{1}[0-9]{8}$/',
            'password' => 'required|string|min:8',
            'is_active' => 'numeric',
        ];
    }

    protected function messages(){
        return [
            'name.required' => ':attribute không được để trống !',
            'name.string' => ':attribute phải là chuỗi !',
            'name.max' => ':attribute tối đa 255 ký tự !',
            'address.string' => ':attribute phải là chuỗi !',
            'address.max' => ':attribute tối đa 255 ký tự!',
            'role_id.numeric' => ':attribute chưa đúng !',
            'role_id.exists' => ':attribute không tồn tại !',
            'store_id.numeric' => ':attribute chưa đúng !',
            'store_id.exists' => ':attribute không tồn tại !',
            'ward_id.required' => ':attribute không được để trống !',
            'district_id.required' => ':attribute không được để trống !',
            'province_id.required' => ':attribute không được để trống !',
            'phone.required' => ':attribute không được để trống !',
            'phone.string' => ':attribute phải là chuỗi !',
            'phone.min' => ':attribute phải đủ 10 ký tự !',
            'phone.unique' => ':attribute đã được đăng ký !',
            'phone.regex' => ':attribute chưa đúng định dạng VD: 0946636842 !',
            'password.required' => ':attribute không được để trống !',
            'password.string' => ':attribute phải là chuỗi !',
            'password.min' => ':attribute tối thiểu 8 ký tự !',
            'is_active.numeric' => ':attribute chưa đúng !',
        ];
    }

    protected function attributes(){
        return [
            'name' => 'Họ tên',
            'address' => 'Địa chỉ',
            'role_id' => 'Vai trò',
            'store_id' => 'Cửa hàng',
            'ward_id' => 'Xã/Phường/Thị trấn',
            'district_id' => 'Quận/Huyện',
            'province_id' => 'Tỉnh/Thành phố',
            'phone' => 'Số điện thoại',
            'password' => 'Mật khẩu',
            'is_active' => 'Trạng thái',
        ];
    }
}

?>
