<?php

namespace App\Http\Validators\Role;

use App\Http\Validators\ValidatorBase;

class RoleCreateValidator extends ValidatorBase{
    protected function rules(){
        return [
            'code' => 'required|string|max:50|unique:roles,code',
            'name' => 'required|string|max:50',
            'level' => 'required|numeric',
            'is_active' => 'numeric',
        ];
    }

    protected function messages(){
        return [
            'code.required' => ':attribute không được để trống !',
            'code.string' => ':attribute phải là chuỗi !',
            'code.max' => ':attribute tối đa 50 ký tự !',
            'code.unique' => ':attribute đã tồn tại !',
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
            'code' => 'Mã vai trò',
            'name' => 'Tên vai trò',
            'level' => 'Cấp',
            'is_active' => 'Kích hoạt'
        ];
    }
}

?>
