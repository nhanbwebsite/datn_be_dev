<?php

namespace App\Http\Validators\Cart;

use App\Http\Validators\ValidatorBase;

class CartCreateValidator extends ValidatorBase
{

    protected function rules(){
        return [
            'product_id' => 'required|numeric|exists:products,id',
            'variant_id' => 'required|numeric|exists:productVariant,id',
            'quantity' => 'required|numeric|min:1',
            'email' => 'string|max:255',
            'address' => 'string|max:255',
            'product_id' => 'required|numeric|exists:products,id',
            'variant_id' => 'required|numeric|exists:productVariant,id',
            'quantity' => 'required|numeric|min:1',
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
            'quantity.required' => ':attribute không được để trống !',
            'quantity.numeric' => ':attribute phải là số !',
            'quantity.min' => ':attribute tối thiểu là 1 !',
            'email.string' => ':attribute phải là chuỗi !',
            'email.email' => ':attribute chưa đúng định dạng ! VD: duynh123@gmail.com',
            'email.max' => ':attribute tối đa 255 ký tự !',
            'address.max' => ':attribute tối đa 255 ký tự !',
            'address.string' => ':attribute phải là chuỗi !',
            'product_id.required' => ':attribute không được để trống !',
            'product_id.numeric' => ':attribute phải là số !',
            'product_id.exists' => ':attribute không tồn tại !',
            'variant_id.required' => ':attribute không được để trống !',
            'variant_id.numeric' => ':attribute phải là số !',
            'variant_id.exists' => ':attribute không tồn tại !',
            'quantity.required' => ':attribute không được để trống !',
            'quantity.numeric' => ':attribute phải là số !',
            'quantity.min' => ':attribute tối thiểu là 1 !',
        ];
    }

    protected function attributes(){
        return [
            'product_id' => 'ID sản phẩm',
            'variant_id' => 'ID biến thể sản phẩm',
            'quantity' => 'Số lượng',
            'email' => 'Email',
            'address' => 'Địa chỉ',
            'product_id' => 'ID sản phẩm',
            'variant_id' => 'ID biến thể sản phẩm',
            'quantity' => 'Số lượng',
        ];
    }
}

?>
