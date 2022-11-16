<?php

namespace App\Http\Validators\File;

use App\Http\Validators\ValidatorBase;

class FileUploadValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'files' => 'required|file'
        ];
    }

    protected function messages(){
        return [
            'files.required' => ':attribute không được để trống !',
            'files.file' => ':attribute phải là chuỗi !',
        ];
    }

    protected function attributes(){
        return [
            'files' => 'File',
        ];
    }
}

?>
