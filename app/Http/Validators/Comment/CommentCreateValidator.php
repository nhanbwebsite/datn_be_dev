<?php

namespace App\Http\Validators\Comment;

use App\Http\Validators\ValidatorBase;

class CommentCreateValidator extends ValidatorBase{
    protected function rules(){
        return [
            'user_id' => 'numeric|exists:users,id',
            'product_id' => 'required|numeric',
            'content' => 'required',
        ];
    }

    protected function messages(){
        return [
            'user_id.required' => ':attribute không được để trống !',
            'user_id.numeric' => ':attribute chưa đúng !',
            'product_id.required' => ':attribute không được để trống !',
            'product_id.numeric' => ':attribute chưa đúng !',
            'content.required' => ':attribute không được để trống !',
        ];
    }

    protected function attributes(){
        return [
            'user_id' => 'Mã người dùng',
            'product_id' => 'ID sản phẩm',
            'content' => 'Nội dung',
        ];
    }
}

?>
