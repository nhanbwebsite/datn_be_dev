<?php

namespace App\Http\Validators\Order;

use App\Http\Validators\ValidatorBase;

class OrderDetailCreateValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'product_id' => 'required|numeric|exists:products,id',
            'variant_id' => 'required|numeric|exists:productVariant,id',
            'color_id' => 'required|numeric|exists:colors,id',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
        ];
    }

    protected function messages(){
        return [
            'product_id.required' => ':attribute không được để trống !',
            'product_id.numeric' => ':attribute phải là số !',
            'product_id.exists' => ':attribute không tồn tại !',
            'variant_id.required' => ':attribute không được để trống !',
            'variant_id.numeric' => ':attribute phải là số !',
            'variant_id.exists' => ':attribute không tồn tại !',
            'color_id.required' => ':attribute không được để trống !',
            'color_id.numeric' => ':attribute phải là số !',
            'color_id.exists' => ':attribute không tồn tại !',
            'quantity.required' => ':attribute không được để trống !',
            'quantity.numeric' => ':attribute phải là số !',
            'price.required' => ':attribute không được để trống !',
            'price.numeric' => ':attribute phải là số !',
        ];
    }

    protected function attributes(){
        return [
            'product_id' => 'ID sản phẩm',
            'variant_id' => 'ID biến thể',
            'color_id' => 'ID màu sắc',
            'quantity' => 'Số lượng mua',
            'price' => 'Giá bán',
        ];
    }
}

?>
