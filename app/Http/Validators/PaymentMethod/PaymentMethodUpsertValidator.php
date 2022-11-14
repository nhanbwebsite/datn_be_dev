<?php

namespace App\Http\Validators\PaymentMethod;

use App\Http\Validators\ValidatorBase;

class PaymentMethodUpsertValidator extends ValidatorBase{
    protected function rules(){
        return [
            'name' => 'required|string|max:50',
            'code' => 'required|string|max:50|unique_deleted_at_null:payment_methods,code',
            'is_active' => 'numeric',
        ];
    }

    protected function messages(){
        return [
            'name.required' => ':attribute không được để trống !',
            'name.string' => ':attribute phải là chuỗi !',
            'name.max' => ':attribute tối đa 50 ký tự !',
            'code.required' => ':attribute không được để trống !',
            'code.string' => ':attribute phải là chuỗi !',
            'code.unique_deleted_at_null' => ':attribute đã tồn tại !',
            'code.max' => ':attribute tối đa 50 ký tự !',
            'is_active.numeric' => ':attribute chưa đúng !',
        ];
    }

    protected function attributes(){
        return [
            'name' => 'Tên phương thức thanh toán',
            'code' => 'Mã phương thức thanh toán',
            'is_active' => 'Kích hoạt'
        ];
    }
}

?>
