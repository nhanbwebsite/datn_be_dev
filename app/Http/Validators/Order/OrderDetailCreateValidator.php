<?php

namespace App\Http\Validators\Order;

use App\Http\Validators\ValidatorBase;

class OrderDetailCreateValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'product_id' => 'required|numeric|exists:products,id',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
        ];
    }

    protected function messages(){
        return [
            'product_id.required' => ':attribute không được để trống !',
            'product_id.numeric' => ':attribute phải là số !',
            'product_id.exists' => ':attribute không tồn tại !',
            'quantity.required' => ':attribute không được để trống !',
            'quantity.numeric' => ':attribute phải là số !',
            'price.required' => ':attribute không được để trống !',
            'price.numeric' => ':attribute phải là số !',
        ];
    }

    protected function attributes(){
        return [
            'product_id' => 'Mã sản phẩm',
            'quantity' => 'Số lượng mua',
            'price' => 'Giá bán',
        ];
    }
}

?>
