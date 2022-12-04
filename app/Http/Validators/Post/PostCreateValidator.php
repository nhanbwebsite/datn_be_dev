<?php

namespace App\Http\Validators\Post;

use App\Http\Validators\ValidatorBase;

class PostCreateValidator extends ValidatorBase
{
    protected function rules(){
        return [
            // 'category_id' => 'required|exists:post_categories,id',
            'subcategory_id' => 'required|exists:sub_categories,id',
            'title' => 'required|max:255',
            'slug' => 'string|max:255|unique_deleted_at_null:posts,slug',
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
            'category_id.required' => ':attribute không được để trống !',
            'category_id.exists' => ':attribute không tồn tại !',
            'title.required' => ':attribute không được để trống !',
            'title.max' => ':attribute đã vượt qua độ dài cho phép !',
            'slug.string' => ':attribute phải là chuỗi !',
            'slug.max' => ':attribute tối đa 255 ký tự !',
            'slug.unique_deleted_at_null' => ':attribute đã tồn tại !',
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
            'category_id'=>'Mã danh mục bài viết',
            'title'=>'Tiêu đề',
            'slug' => 'Slug',
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
