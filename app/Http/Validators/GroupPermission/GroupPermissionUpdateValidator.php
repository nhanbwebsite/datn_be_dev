<?php

namespace App\Http\Validators\GroupPermission;

use App\Http\Validators\ValidatorBase;

class GroupPermissionUpdateValidator extends ValidatorBase{
    protected function rules(){
        return [
            'name' => 'required|string|max:50',
            'table_name' => 'string',
            'is_active' => 'numeric',
        ];
    }

    protected function messages(){
        return [
            'name.required' => ':attribute không được để trống !',
            'name.string' => ':attribute phải là chuỗi !',
            'name.max' => ':attribute tối đa 50 ký tự !',
            'table_name.string' => ':attribute phải là chuỗi !',
            'is_active.numeric' => ':attribute phải là số !',
        ];
    }

    protected function attributes(){
        return [
            'name' => 'Tên nhóm quyền',
            'table_name' => 'Tên bảng',
            'is_active' => 'Kích hoạt',
        ];
    }
}

?>
