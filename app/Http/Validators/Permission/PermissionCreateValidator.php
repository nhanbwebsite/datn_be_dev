<?php

namespace App\Http\Validators\Permission;

use App\Http\Validators\ValidatorBase;

class PermissionCreateValidator extends ValidatorBase{
    protected function rules(){
        return [
            'code' => 'required|string|max:50|unique_deleted_at_null:permissions,code',
            'name' => 'required|string|max:50',
            'group_id' => 'required|numeric|exists:group_permissions,id',
            'is_active' => 'numeric',
        ];
    }

    protected function messages(){
        return [
            'code.required' => ':attribute không được để trống !',
            'code.string' => ':attribute phải là chuỗi !',
            'code.max' => ':attribute tối đa 50 ký tự !',
            'code.unique_deleted_at_null' => ':attribute đã tồn tại !',
            'name.required' => ':attribute không được để trống !',
            'name.string' => ':attribute phải là chuỗi !',
            'name.max' => ':attribute tối đa 50 ký tự !',
            'group_id.required' => ':attribute không được để trống !',
            'group_id.numeric' => ':attribute phải là số !',
            'group_id.exists' => ':attribute không tồn tại !',
            'is_active.numeric' => ':attribute chưa đúng !',
        ];
    }

    protected function attributes(){
        return [
            'code' => 'Mã quyền',
            'name' => 'Tên quyền',
            'group_id' => 'Mã nhóm quyền',
            'is_active' => 'Kích hoạt'
        ];
    }
}

?>
