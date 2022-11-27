<?php

namespace App\Http\Validators\ProductImportSlip;

use App\Http\Validators\ValidatorBase;

class ProductImportSlipDetailCreateValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'product_id' => 'required|numeric|exists:products,id',
            'quantity_import' => 'required|numeric',
            'price_import' => 'required|numeric',
            'pro_variant_id' => 'required|numeric'
        ];
    }

    protected function messages(){
        return [
            'product_id.required' => ':attribute không được để trống !',
            'product_id.numeric' => ':attribute phải là số !',
            'product_id.exists' => ':attribute không tồn tại !',
            'quantity_import.required' => ':attribute không được để trống !',
            'quantity_import.numeric' => ':attribute phải là số !',
            'price_import.required' => ':attribute không được để trống !',
            'price_import.numeric' => ':attribute phải là số !',
            'pro_variant_id.required' => ':attribute không được để trống',
            'pro_variant_id.numeric' => ':attribute phải là số !',
        ];
    }

    protected function attributes(){
        return [
            'product_id' => 'ID sản phẩm',
            'quantity_import' => 'Số lượng nhập',
            'price_import' => 'Giá nhập',
            'Chi tiết biến thể sản phẩm'
        ];
    }
}
?>
