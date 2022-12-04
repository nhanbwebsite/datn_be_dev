<?php

namespace App\Http\Validators\Contact;

use App\Http\Validators\ValidatorBase;

class ContactCreateValidator extends ValidatorBase{
    protected function rules(){
        return [
            'category_id' => 'required|exists:footer_category,id',
            'slug' => 'string|max:255|unique_deleted_at_null:contact,slug',
            'name' => 'required|string|max:255',
            'phone'=> 'required|string|regex:/^0[2-9]{1}[0-9]{8}$/',
            'time'=> 'required|',
            'is_active' => 'numeric',
        ];
    }

    protected function messages(){
        return [
            'category_id.required' => ':attribute không được để trống !',
            'category_id.exists' => ':attribute không tồn tại !',
            'slug.string' => ':attribute phải là chuỗi !',
            'slug.max' => ':attribute tối đa 255 ký tự !',
            'slug.unique_deleted_at_null' => ':attribute đã tồn tại !',
            'phone.required' => ':attribute không được để trống !',
            'phone.string' => ':attribute phải là chuỗi !',
            'phone.regex' => ':attribute chưa đúng định dạng ! VD: 0946636842',
            'name.required' => ':attribute không được để trống !',
            'name.string' => ':attribute phải là chuỗi !',
            'name.max' => ':attribute tối đa 255 ký tự !',
            'is_active.numeric' => ':attribute chưa đúng !',
        ];
    }

    protected function attributes(){
        return [
            'slug' => 'Slug',
            'name' => 'Tên liên hệ của shop',
            'phone' => ' Số điện thoại liên hệ',
            'time' => 'Thời gian hoạt động',
            'is_active' => 'Kích hoạt'
        ];
    }
}

?>
