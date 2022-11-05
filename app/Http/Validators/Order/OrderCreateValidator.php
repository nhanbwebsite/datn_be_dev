<?php

namespace App\Http\Validators\Order;

use App\Http\Validators\ValidatorBase;

class OrderCreateValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'fee_ship' => 'required|numeric',
            'address_note_id' => 'required|numeric|exists:address_notes,id',
            'user_id' => 'required|numeric|exists:users,id',
            'payment_method_id' => 'required|numeric|exists:payment_methods,id',
            'shipping_method_id' => 'required|numeric',
            'details' => 'required',
        ];
    }

    protected function messages(){
        return [
            'fee_ship.required' => ':attribute không được để trống !',
            'fee_ship.numeric' => ':attribute phải là số !',
            'address_note_id.required' => ':attribute không được để trống !',
            'address_note_id.numeric' => ':attribute phải là số !',
            'address_note_id.exists' => ':attribute không tồn tại !',
            'user_id.required' => ':attribute không được để trống !',
            'user_id.numeric' => ':attribute phải là số !',
            'user_id.exists' => ':attribute không tồn tại !',
            'payment_method_id.required' => ':attribute không được để trống !',
            'payment_method_id.numeric' => ':attribute phải là số !',
            'payment_method_id.exists' => ':attribute không tồn tại !',
            'shipping_method_id.required' => ':attribute không được để trống !',
            'shipping_method_id.numeric' => ':attribute phải là số !',
            'details.required' => ':attribute không được để trống !',
        ];
    }

    protected function attributes(){
        return [
            'fee_ship' => 'Phí vận chuyển',
            'address_note_id' => 'Địa chỉ người nhận',
            'user_id' => 'Mã người dùng',
            'payment_method_id' => 'Phương thức thanh toán',
            'shipping_method_id' => 'Hình thức vận chuyển',
            'details' => 'Chi tiết đơn hàng',
        ];
    }
}

?>
