<?php

namespace App\Http\Validators\Specification;

use App\Http\Validators\ValidatorBase;

class SpecificationUpsertValidator extends ValidatorBase{
    protected function rules(){
        return [
            'id_category' => 'required|numeric',
            'name' => 'required|string|max:50',
            'infomation' => 'required',
            'is_active' => 'numeric',
        ];
    }

    protected function messages(){
        return [
            'id_category.required' => ':attribute không được để trống !',
            'id_category.numeric' => ':attribute phải là số !',
            'name.required' => ':attribute không được để trống !',
            'name.string' => ':attribute phải là chuỗi !',
            'name.max' => ':attribute tối đa 50 ký tự !',
            'infomation.required' => ':attribute không được để trống !',
            'is_active.numeric' => ':attribute phải là số !',
        ];
    }

    protected function attributes(){
        return [
            'id_category' => 'Mã danh mục',
            'name' => 'Tên thông số',
            'infomation' => 'Thông số',
            'is_active' => 'Kích hoạt'
        ];
    }
}

?>
