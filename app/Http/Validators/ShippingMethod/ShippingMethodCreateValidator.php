<?php

namespace App\Http\Validators\ShippingMethod;

use App\Http\Validators\ValidatorBase;

class ShippingMethodCreateValidator extends ValidatorBase{
    protected function rules(){
        return [
            'code' => 'required|string|max:50|unique_deleted_at_null:shipping_methods,code',
            'name' => 'required|string|max:50',
            'is_active' => 'numeric',
        ];
    }

    protected function messages(){
        return [
            'code.required' => ':attribute không được để trống !',
            'code.numeric' => ':attribute phải là số !',
            'code.max' => ':attribute tối đã 50 ký tự !',
            'code.unique_deleted_at_null' => ':attribute đã tồn tại !',
            'name.required' => ':attribute không được để trống !',
            'name.string' => ':attribute phải là số !',
            'name.max' => ':attribute tối đã 50 ký tự !',
            'is_active.numeric' => ':attribute phải là số !',
        ];
    }

    protected function attributes(){
        return [
            'code' => 'Mã hình thức vận chuyển',
            'name' => 'Tên hình thức vận chuyển',
            'is_active' => 'Kích hoạt'
        ];
    }
}

?>
