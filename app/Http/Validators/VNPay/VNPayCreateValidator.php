<?php

namespace App\Http\Validators\VNPay;

use App\Http\Validators\ValidatorBase;

class VNPayCreateValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'amount' => 'required|numeric',
            'returnUrl' => 'required|string',
        ];
    }

    protected function messages(){
        return [
            'amount.required' => ':attribute không được để trống !',
            'amount.numeric' => ':attribute phải là số !',
            'returnUrl.required' => ':attribute không được để trống !',
            'returnUrl.string' => ':attribute phải là chuỗi !',
        ];
    }

    protected function attributes(){
        return [
            'amount' => 'Số tiền thanh toán',
            'returnUrl' => 'URL thông báo kết quả giao dịch',
        ];
    }
}

?>
