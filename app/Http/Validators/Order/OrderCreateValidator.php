<?php

namespace App\Http\Validators\Order;

use App\Http\Validators\ValidatorBase;

class OrderCreateValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'fee_ship' => 'required|numeric',
            'address_note_id' => 'required|numeric',
            'payment_method_id' => 'required|numeric',
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
            'payment_method_id.required' => ':attribute không được để trống !',
            'payment_method_id.numeric' => ':attribute phải là số !',
            'shipping_method_id.required' => ':attribute không được để trống !',
            'shipping_method_id.numeric' => ':attribute phải là số !',
            'details.required' => ':attribute không được để trống !',
        ];
    }

    protected function attributes(){
        return [
            'fee_ship' => 'Phí vận chuyển',
            'address_id' => 'Thông tin người nhận',
            'payment_method_id' => 'Phương thức thanh toán',
            'shipping_method_id' => 'Hình thức vận chuyển',
            'details' => 'Chi tiết đơn hàng',
        ];
    }
}

?>
