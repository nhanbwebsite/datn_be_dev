<?php

namespace App\Http\Validators\VNPay;

use App\Http\Validators\ValidatorBase;

class VNPayCreateValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'amount' => 'required|numeric',
        ];
    }

    protected function messages(){
        return [
            'amount.required' => ':attribute không được để trống !',
            'amount.numeric' => ':attribute phải là số !',
        ];
    }

    protected function attributes(){
        return [
            'amount' => 'Số tiền thanh toán',
        ];
    }
}

?>
