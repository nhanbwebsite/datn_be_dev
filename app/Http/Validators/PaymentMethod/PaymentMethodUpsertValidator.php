<?php

namespace App\Http\Validators\PaymentMethod;

use App\Http\Validators\ValidatorBase;

class PaymentMethodUpsertValidator extends ValidatorBase{
    protected function rules(){
        return [
            'name' => 'required|string|max:50',
            'is_active' => 'numeric',
        ];
    }

    protected function messages(){
        return [
            'name.required' => ':attribute không được để trống !',
            'name.string' => ':attribute phải là chuỗi !',
            'name.max' => ':attribute tối đa 50 ký tự !',
            'is_active.numeric' => ':attribute chưa đúng !',
        ];
    }

    protected function attributes(){
        return [
            'name' => 'Tên phương thức thanh toán',
            'is_active' => 'Kích hoạt'
        ];
    }
}

?>
