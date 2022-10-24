<?php

namespace App\Http\Validators\Permission;

use App\Http\Validators\ValidatorBase;

class PermissionUpdateValidator extends ValidatorBase{
    protected function rules(){
        return [
            'name' => 'required|string|max:50',
            'group_id' => 'required|numeric|exists:group_permissions,id',
        ];
    }

    protected function messages(){
        return [
            'name.required' => ':attribute không được để trống !',
            'name.string' => ':attribute phải là chuỗi !',
            'name.max' => ':attribute tối đa 50 ký tự !',
            'group_id.required' => ':attribute không được để trống !',
            'group_id.numeric' => ':attribute phải là số !',
            'group_id.exists' => ':attribute không tồn tại !',
        ];
    }

    protected function attributes(){
        return [
            'name' => 'Tên quyền',
            'group_id' => 'Mã nhóm quyền',
        ];
    }
}

?>
