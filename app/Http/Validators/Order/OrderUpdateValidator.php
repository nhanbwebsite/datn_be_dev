<?php

namespace App\Http\Validators\Order;

use App\Http\Validators\ValidatorBase;

class OrderUpdateValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'status' => 'required|numeric',
            'address_note_id' => 'required|numeric|exists:address_notes,id',
            'payment_method_id' => 'required|numeric|exists:payment_methods,id',
            'shipping_method_id' => 'required|numeric',
        ];
    }

    protected function messages(){
        return [
            'status.required' => ':attribute không được để trống !',
            'status.numeric' => ':attribute phải là số !',
            'address_note_id.required' => ':attribute không được để trống !',
            'address_note_id.numeric' => ':attribute phải là số !',
            'payment_method_id.required' => ':attribute không được để trống !',
            'payment_method_id.numeric' => ':attribute phải là số !',
            'shipping_method_id.required' => ':attribute không được để trống !',
            'shipping_method_id.numeric' => ':attribute phải là số !',
        ];
    }

    protected function attributes(){
        return [
            'status' => 'Trạng thái đơn hàng',
            'address_id' => 'Thông tin người nhận',
            'payment_method_id' => 'Phương thức thanh toán',
            'shipping_method_id' => 'Hình thức vận chuyển',
        ];
    }
}

?>
