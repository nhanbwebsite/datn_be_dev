<?php

namespace App\Http\Validators\OrderStatus;

use App\Http\Validators\ValidatorBase;

class OrderStatusUpsertValidator extends ValidatorBase{
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
            'name' => 'Tên trạng thái',
            'is_active' => 'Kích hoạt'
        ];
    }
}

?>
