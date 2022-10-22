<?php

namespace App\Http\Validators\User;

use App\Http\Validators\ValidatorBase;

class CommentCreateValidator extends ValidatorBase{
    protected function rules(){
        return [
            'user_id' => 'required|numeric|exists:users,id',
            'post_id' => 'required|numeric|exists:posts,id',
            'content' => 'required',
        ];
    }

    protected function messages(){
        return [
            'user_id.required' => ':attribute không được để trống !',
            'user_id.numeric' => ':attribute chưa đúng !',
            'post_id.required' => ':attribute không được để trống !',
            'post_id.numeric' => ':attribute chưa đúng !',
            'content.required' => ':attribute không được để trống !',
        ];
    }

    protected function attributes(){
        return [
            'user_id' => 'Mã người dùng',
            'post_id' => 'Mã bài viết',
            'content' => 'Nội dung',
        ];
    }
}

?>
