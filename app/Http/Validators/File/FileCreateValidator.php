<?php

namespace App\Http\Validators\File;

use App\Http\Validators\ValidatorBase;

class FileCreateValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'slug' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'extension' => 'required|string|max:50',
        ];
    }

    protected function messages(){
        return [
            'slug.required' => ':attribute không được để trống !',
            'slug.string' => ':attribute phải là chuỗi !',
            'slug.max' => ':attribute tối đa 255 ký tự !',
            'name.required' => ':attribute không được để trống !',
            'name.string' => ':attribute phải là chuỗi !',
            'name.max' => ':attribute tối đa 255 ký tự !',
            'extension.required' => ':attribute không được để trống !',
            'extension.string' => ':attribute phải là chuỗi !',
            'extension.max' => ':attribute tối đa 50 ký tự !',
        ];
    }

    protected function attributes(){
        return [
            'slug' => 'Slug',
            'name' => 'Tên file',
            'extension' => 'Phần mở rộng',
        ];
    }
}

?>
