<?php

namespace App\Http\Validators\Comment;

use App\Http\Validators\ValidatorBase;

class CommentUpdateValidator extends ValidatorBase{
    protected function rules(){
        return [
            'content' => 'required',
            'is_active' => 'required|numeric',
        ];
    }

    protected function messages(){
        return [
            'content.required' => ':attribute không được để trống !',
            'is_active.required' => ':attribute không được để trống !',
            'is_active.numeric' => ':attribute chưa đúng !',
        ];
    }

    protected function attributes(){
        return [
            'content' => 'Nội dung',
            'is_active' => 'Trạng thái',
        ];
    }
}

?>
