<?php

namespace App\Http\Validators\Cart;

use App\Http\Validators\ValidatorBase;

class CartDetailUpsertValidator extends ValidatorBase
{

    protected function rules(){
        return [
            'product_id' => 'required|numeric|exists:products,id',
            'quantity' => 'required|numeric|min:1',
        ];
    }

    protected function messages(){
        return [
            'product_id.required' => ':attribute không được để trống !',
            'product_id.numeric' => ':attribute phải là số !',
            'product_id.exists' => ':attribute không tồn tại !',
            'quantity.required' => ':attribute không được để trống !',
            'quantity.numeric' => ':attribute phải là số !',
            'quantity.min' => ':attribute tối thiểu là 1 !',
        ];
    }

    protected function attributes(){
        return [
            'product_id' => 'ID sản phẩm',
            'quantity' => 'Số lượng'
        ];
    }
}

?>
