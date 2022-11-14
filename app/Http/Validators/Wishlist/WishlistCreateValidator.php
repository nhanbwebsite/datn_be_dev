<?php

namespace App\Http\Validators\Wishlist;

use App\Http\Validators\ValidatorBase;

class WishlistCreateValidator extends ValidatorBase{
    protected function rules(){
        return [
            'user_id' => 'numeric|exists:users,id',
            'product_id' => 'required|numeric|exists:products,id',
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
