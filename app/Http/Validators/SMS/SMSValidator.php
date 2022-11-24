<?php

namespace App\Http\Validators\SMS;

use App\Http\Validators\ValidatorBase;

class SMSValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'phone' => 'required|string',
            'message' => 'required|string',
        ];
    }

    protected function messages(){
        return [
            'phone.required' => ':attribute không được để trống !',
            'phone.string' => ':attribute phải là chuỗi !',
            'message.required' => ':attribute không được để trống !',
            'message.string' => ':attribute phải là chuỗi !',
        ];
    }

    protected function attributes(){
        return [
            'phone' => 'Số điện thoại',
            'message' => 'Nội dung tin nhắn',
        ];
    }
}

?>
