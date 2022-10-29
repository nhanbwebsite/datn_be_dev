<?php

namespace App\Http\Validators\Auth;

use App\Http\Validators\ValidatorBase;

class LoginValidator extends ValidatorBase{
    protected function rules(){
        return [
            'phone' => 'required|string|regex:/^0[2-9]{1}[0-9]{8}$/',
            'password' => 'required|string|min:8',
        ];
    }

    protected function messages(){
        return [
            'phone.required' => ':attribute không được để trống !',
            'phone.string' => ':attribute phải là chuỗi !',
            'phone.regex' => ':attribute chưa đúng định dạng ! VD: 0946636842',
            'password.required' => ':attribute không được để trống !',
            'password.string' => ':attribute phải là chuỗi !',
            'password.min' => ':attribute tối thiểu 8 ký tự !',
        ];
    }

    protected function attributes(){
        return [
            'phone' => 'Số điện thoại',
            'password' => 'Mật khẩu',
        ];
    }
}

?>
