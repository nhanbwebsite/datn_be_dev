<?php

namespace App\Http\Validators\GroupPermission;

use App\Http\Validators\ValidatorBase;

class GroupPermissionCreateValidator extends ValidatorBase{
    protected function rules(){
        return [
            'code' => 'required|string|max:50|unique_deleted_at_null:group_permissions,code',
            'name' => 'required|string|max:50',
            'table_name' => 'string',
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
            'table_name.string' => ':attribute phải là chuỗi !',
            'is_active.numeric' => ':attribute chưa đúng !',
        ];
    }

    protected function attributes(){
        return [
            'code' => 'Mã nhóm quyền',
            'name' => 'Tên nhóm quyền',
            'table_name' => 'Tên bảng',
            'is_active' => 'Kích hoạt',
        ];
    }
}

?>
