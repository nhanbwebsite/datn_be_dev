<?php

namespace App\Http\Validators\FooterContent;

use App\Http\Validators\ValidatorBase;

class FooterContentUpdateValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'category_id' => 'required|exists:footer_category,id',
            'title' => 'required|max:255',
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
            'content.required'=> ':attribute không được để trống !',
            'is_active.numeric' => ':attribute phải là số !',
        ];
    }

    protected function attributes(){
        return [
            'category_id'=>'Mã danh mục bài viết',
            'title'=>'Tiêu đề',
            'content'=>'Nội dung',
            'is_active'=>'Trạng thái',
        ];
    }
}

?>
