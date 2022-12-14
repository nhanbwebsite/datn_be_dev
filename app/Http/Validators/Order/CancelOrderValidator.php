<?php

namespace App\Http\Validators\Order;

use App\Http\Validators\ValidatorBase;

class CancelOrderValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'cancel_reason' => 'required|string|max:255',
        ];
    }

    protected function messages(){
        return [
            'cancel_reason.required' => ':attribute không được để trống !',
            'cancel_reason.string' => ':attribute phải là chuỗi !',
            'cancel_reason.max' => ':attribute tối đa 255 ký tự !',
        ];
    }

    protected function attributes(){
        return [
            'cancel_reason' => 'Lý do hủy đơn',
        ];
    }
}

?>
