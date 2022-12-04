<?php

namespace App\Http\Validators\Post;

use App\Http\Validators\ValidatorBase;

class PostUpdateValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'subcategory_id' => 'required|exists:sub_categories,id',
            'title' => 'required|max:255',
            'short_des'=>'required',
            'content_post'=>'required',
            'image' => 'string',
            'meta_title' => 'required|max:120',
            'meta_keywords' => 'required|max:255',
            'is_active'=> 'numeric',
        ];
    }

    protected function messages(){
        return [
            'subcategory_id.required' => ':attribute không được để trống !',
            'subcategory_id.exists' => ':attribute không tồn tại !',
            'title.required' => ':attribute không được để trống !',
            'title.max' => ':attribute đã vượt qua độ dài cho phép !',
            'short_des.required' => ':attribute không được để trống !',
            'short_des.max' => ':attribute đã vượt qua độ dài cho phép !',
            'content_post.required'=> ':attribute không được để trống !',
            'image.string' => 'attribute phải là chuỗi',
            'meta_title.required' => ':attribute không được để trống !',
            'meta_title.max' => ':attribute đã vượt qua độ dài cho phép !',
            'meta_keywords.required'=> ':attribute không được để trống !',
            'meta_keywords.max'=> ':attribute đã vượt qua độ dài cho phép !',
            'is_active.numeric' => ':attribute phải là số !',
        ];
    }

    protected function attributes(){
        return [
            'subcategory_id'=>'Mã danh mục bài viết',
            'title'=>'Tiêu đề',
            'short_des'=>'Mô tả',
            'content_post'=>'Nội dung',
            'image'=>'Ảnh bài viết',
            'meta_title'=>'Thẻ tiêu đề bài viết',
            'meta_keywords'=>'Từ khóa bài viết',
            'is_active'=>'Trạng thái',
        ];
    }
}

?>
