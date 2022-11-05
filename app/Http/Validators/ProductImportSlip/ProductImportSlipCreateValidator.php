<?php

namespace App\Http\Validators\ProductImportSlip;

use App\Http\Validators\ValidatorBase;

class ProductImportSlipCreateValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'name' => 'required|min:6|max:255',
            'code' => 'max:50|unique_deleted_at_null:product_import_slip,code',
            'warehouse_id' => 'required|numeric|exists:warehouses,id',
            'status' => 'required|regex:/^[X,O]$/',
            'details' => 'required'
        ];
    }

    protected function messages(){
        return [
            'name.required' => ':attribute không được để trống !',
            'name.min' => ':attribute tối thiểu 6 ký tự !',
            'name.max' => ':attribute tối đa 255 ký tự !',
            'code.max' => ':attribute tối đa 50 ký tự !',
            'warehouse_id.required' => ':attribute không được để trống !',
            'warehouse_id.numeric' => ':attribute phải là số !',
            'warehouse_id.exists' => ':attribute không tồn tại !',
            'status.required' => ':attribute không được để trống !',
            'status.regex' => ':attribute chưa đúng (X/O) !',
            'details.required' => ':attribute không được để trống !',
        ];
    }

    protected function attributes(){
        return [
            'name' => 'Tên phiếu nhập',
            'code' => 'Mã phiếu nhập',
            'warehouse_id' => 'Kho sản phẩm',
            'status' => 'Trạng thái phiếu nhập',
            'details' => 'Chi tiết phiếu nhập',
        ];
    }
}
?>
