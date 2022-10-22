<?php

namespace App\Http\Validators\User;

use App\Http\Validators\ValidatorBase;

class WishlistCreateValidator extends ValidatorBase{
    protected function rules(){
        return [
            'user_id' => 'required|numeric',
            'product_id' => 'required|numeric',
        ];
    }

    protected function messages(){
        return [
            'user_id.required' => ':attribute không được để trống !',
            'user_id.numeric' => ':attribute chưa đúng !',
            'product_id.required' => ':attribute không được để trống !',
            'product_id.numeric' => ':attribute chưa đúng !',
        ];
    }

    protected function attributes(){
        return [
            'user_id' => 'Mã người dùng',
            'product_id' => 'Mã sản phẩm',
        ];
    }
}

?>
