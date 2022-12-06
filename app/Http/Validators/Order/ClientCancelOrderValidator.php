<?php

namespace App\Http\Validators\Order;

use App\Http\Validators\ValidatorBase;

class ClientCancelOrderValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'order_code' => 'required|string|exists:orders,code',
            'cancel_reason' => 'required|string|max:255',
        ];
    }

    protected function messages(){
        return [
            'order_code.required' => ':attribute không được để trống !',
            'order_code.string' => ':attribute phải là chuỗi !',
            'order_code.exists' => ':attribute không tồn tại !',
            'cancel_reason.required' => ':attribute không được để trống !',
            'cancel_reason.string' => ':attribute phải là chuỗi !',
            'cancel_reason.max' => ':attribute tối đa 255 ký tự !',
        ];
    }

    protected function attributes(){
        return [
            'order_code' => 'Mã đơn hàng',
            'cancel_reason' => 'Lý do hủy đơn',
        ];
    }
}

?>
