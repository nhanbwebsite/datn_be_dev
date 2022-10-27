<?php

namespace App\Http\Validators\Auth;

use App\Http\Validators\ValidatorBase;

class RegisterValidator extends ValidatorBase{
    protected function rules(){
        return [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'ward_id' => 'required',
            'district_id' => 'required',
            'province_id' => 'required',
            'phone' => 'required|string|min:10|unique:users,phone|regex:/^0[2-9]{1}[0-9]{8}$/',
            'password' => 'required|string|min:8',
            'password_confirm' => 'required|string|min:8|same:password',
        ];
    }

    protected function messages(){
        return [
            'name.required' => ':attribute không được để trống !',
            'name.string' => ':attribute phải là chuỗi !',
            'name.max' => ':attribute tối đa 255 ký tự !',
            'address.string' => ':attribute phải là chuỗi !',
            'address.max' => ':attribute tối đa 255 ký tự!',
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
            'password.confirmed' => ':attribute không đúng !',
            'password_confirm.same' => ':attribute không đúng !',
            'password_confirm.required' => ':attribute không được để trống !',
            'password_confirm.string' => ':attribute phải là chuỗi !',
            'password_confirm.min' => ':attribute tối thiểu 8 ký tự !',
        ];
    }

    protected function attributes(){
        return [
            'name' => 'Họ tên',
            'address' => 'Địa chỉ',
            'ward_id' => 'Xã/Phường/Thị trấn',
            'district_id' => 'Quận/Huyện',
            'province_id' => 'Tỉnh/Thành phố',
            'phone' => 'Số điện thoại',
            'password' => 'Mật khẩu',
            'password_confirm' => 'Xác nhận mật khẩu',
        ];
    }
}

?>
