<?php

namespace App\Http\Validators\RolePermission;

use App\Http\Validators\ValidatorBase;

class RolePermissionCreateValidator extends ValidatorBase{
    protected function rules(){
        return [
            'role_id' => 'required|numeric|exists:roles,id',
            'permission_id' => 'required|numeric|exists:permissions,id',
            'is_active' => 'numeric',
        ];
    }

    protected function messages(){
        return [
            'role_id.required' => ':attribute không được để trống !',
            'role_id.numeric' => ':attribute phải là số !',
            'role_id.exists' => ':attribute không tồn tại !',
            'permission_id.required' => ':attribute không được để trống !',
            'permission_id.numeric' => ':attribute phải là số !',
            'permission_id.exists' => ':attribute không tồn tại !',
            'is_active.numeric' => ':attribute chưa đúng !',
        ];
    }

    protected function attributes(){
        return [
            'role_id' => 'Mã vai trò',
            'permission_id' => 'Mã quyền',
            'is_active' => 'Kích hoạt'
        ];
    }
}

?>
