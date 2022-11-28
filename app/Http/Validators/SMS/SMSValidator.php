<?php

namespace App\Http\Validators\SMS;

use App\Http\Validators\ValidatorBase;
use Illuminate\Validation\Rule;

class SMSValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'phone' => 'required|string',
            'action' => 'required|check_action_sms',
            // 'message' => 'required|string',
        ];
    }

    protected function messages(){
        return [
            'phone.required' => ':attribute không được để trống !',
            'phone.string' => ':attribute phải là chuỗi !',
            'action.required' => ':attribute không được để trống !',
            'action.check_action_sms' => ':attribute không đúng !',
            // 'message.required' => ':attribute không được để trống !',
            // 'message.string' => ':attribute phải là chuỗi !',
        ];
    }

    protected function attributes(){
        return [
            'phone' => 'Số điện thoại',
            'action' => 'Hành động',
            // 'message' => 'Nội dung tin nhắn',
        ];
    }
}

?>
