<?php

namespace App\Http\Validators\VNPay;

use App\Http\Validators\ValidatorBase;

class VNPayCreateValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'amount' => 'required|numeric',
            'returnUrl' => 'required|url',
        ];
    }

    protected function messages(){
        return [
            'amount.required' => ':attribute không được để trống !',
            'amount.numeric' => ':attribute phải là số !',
            'returnUrl.required' => ':attribute không được để trống !',
            'returnUrl.url' => ':attribute chưa đúng định dạng URL !',
        ];
    }

    protected function attributes(){
        return [
            'amount' => 'Số tiền thanh toán',
            'returUrl' => 'URL thông báo trạng thái thanh toán',
        ];
    }
}

?>
