<?php

namespace App\Http\Validators\OrderStatus;

use App\Http\Validators\ValidatorBase;

class OrderStatusUpsertValidator extends ValidatorBase{
    protected function rules(){
        return [
            'name' => 'required|string|max:50',
            'code' => 'required|string|max:50|unique_deleted_at_null:order_status,code',
            'sort_level' => 'nullable|numeric',
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
            'code.max' => ':attribute tối đa 50 ký tự !',
            'sort_level.numeric' => ':attribute phải là số !',
            'sort_level' => ':attribute không được để trống !',
            'is_active.numeric' => ':attribute chưa đúng !',
        ];
    }

    protected function attributes(){
        return [
            'name' => 'Tên trạng thái',
            'code' => 'Mã trạng thái',
            'sort_level' => 'Thứ tự',
            'is_active' => 'Kích hoạt'
        ];
    }
}

?>
