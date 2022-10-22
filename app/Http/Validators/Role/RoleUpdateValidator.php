<?php

namespace App\Http\Validators\User;

use App\Http\Validators\ValidatorBase;

class RoleUpdateValidator extends ValidatorBase{
    protected function rules(){
        return [
            'name' => 'required|string|max:50',
            'level' => 'required|numeric',
            'is_active' => 'numeric'
        ];
    }

    protected function messages(){
        return [
            'name.required' => ':attribute không được để trống !',
            'name.string' => ':attribute phải là chuỗi !',
            'name.max' => ':attribute tối đa 50 ký tự !',
            'level.required' => ':attribute không được để trống !',
            'level.numeric' => ':attribute phải là số !',
            'is_active.numeric' => ':attribute chưa đúng !',
        ];
    }

    protected function attributes(){
        return [
            'name' => 'Tên vai trò',
            'level' => 'Cấp',
            'is_active' => 'Kích hoạt'
        ];
    }
}

?>
