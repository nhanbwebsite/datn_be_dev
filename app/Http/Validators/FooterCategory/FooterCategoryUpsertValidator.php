<?php

namespace App\Http\Validators\FooterCategory;

use App\Http\Validators\ValidatorBase;

class FooterCategoryUpsertValidator extends ValidatorBase{
    protected function rules(){
        return [
            'slug' => 'string|max:255|',
            'name' => 'required|string|max:255',
            'is_active' => 'numeric',
        ];
    }

    protected function messages(){
        return [
            'slug.string' => ':attribute phải là chuỗi !',
            'slug.max' => ':attribute tối đa 255 ký tự !',
            'name.required' => ':attribute không được để trống !',
            'name.string' => ':attribute phải là chuỗi !',
            'name.max' => ':attribute tối đa 255 ký tự !',
            'is_active.numeric' => ':attribute chưa đúng !',
        ];
    }

    protected function attributes(){
        return [
            'slug' => 'Slug',
            'name' => 'Tên danh mục Footer',
            'is_active' => 'Kích hoạt'
        ];
    }
}

?>
