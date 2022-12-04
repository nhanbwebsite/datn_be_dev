<?php

namespace App\Http\Validators\FooterContent;

use App\Http\Validators\ValidatorBase;

class FooterContentCreateValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'category_id' => 'required|exists:footer_category,id',
            'title' => 'required|max:255',
            'slug' => 'string|max:255|unique_deleted_at_null:footer_content,slug',
            'content'=>'required',
            'is_active'=> 'numeric',
        ];
    }

    protected function messages(){
        return [
            'category_id.required' => ':attribute không được để trống !',
            'category_id.exists' => ':attribute không tồn tại !',
            'title.required' => ':attribute không được để trống !',
            'title.max' => ':attribute đã vượt qua độ dài cho phép !',
            'slug.string' => ':attribute phải là chuỗi !',
            'slug.max' => ':attribute tối đa 255 ký tự !',
            'slug.unique_deleted_at_null' => ':attribute đã tồn tại !',
            'content.required'=> ':attribute không được để trống !',
            'is_active.numeric' => ':attribute phải là số !',
        ];
    }

    protected function attributes(){
        return [
            'category_id'=>'Danh mục footer',
            'title'=>'Tiêu đề',
            'slug' => 'Slug',
            'content'=>'Nội dung',
            'is_active'=>'Trạng thái',
        ];
    }
}

?>
