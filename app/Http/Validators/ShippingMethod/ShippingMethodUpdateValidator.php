<?php

namespace App\Http\Validators\ShippingMethod;

use App\Http\Validators\ValidatorBase;

class ShippingMethodUpdateValidator extends ValidatorBase{
    protected function rules(){
        return [
            'name' => 'required|string|max:50',
            'is_active' => 'numeric',
        ];
    }

    protected function messages(){
        return [
            'name.required' => ':attribute không được để trống !',
            'name.string' => ':attribute phải là số !',
            'name.max' => ':attribute tối đã 50 ký tự !',
            'is_active.numeric' => ':attribute phải là số !',
        ];
    }

    protected function attributes(){
        return [
            'name' => 'Tên hình thức vận chuyển',
            'is_active' => 'Kích hoạt'
        ];
    }
}

?>
