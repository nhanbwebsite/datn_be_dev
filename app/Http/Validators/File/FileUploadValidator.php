<?php

namespace App\Http\Validators\File;

use App\Http\Validators\ValidatorBase;

class FileUploadValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'files' => 'required',
        ];
    }

    protected function messages(){
        return [
            'files.required' => ':attribute không được để trống !',
        ];
    }

    protected function attributes(){
        return [
            'files' => 'File',
        ];
    }
}

?>
