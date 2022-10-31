<?php

namespace App\Http\Validators\Warehouse;

use App\Http\Validators\ValidatorBase;

class WarehouseUpsertValidator extends ValidatorBase{
    protected function rules(){
        return [
            'name' => 'required|string|max:50|unique_deleted_at_null:warehouses,name',
            'address' => 'required|string|max:255',
            'ward_id' => 'required|numeric|exists:wards,id',
            'district_id' => 'required|numeric|exists:districts,id',
            'province_id' => 'required|numeric|exists:provinces,id',
            'is_active' => 'numeric',
        ];
    }

    protected function messages(){
        return [
            'name.required' => ':attribute không được để trống !',
            'name.string' => ':attribute phải là chuỗi !',
            'name.max' => ':attribute tối đa 50 ký tự !',
            'name.unique_deleted_at_null' => ':attribute đã tồn tại !',
            'address.required' => ':attribute không được để trống !',
            'address.string' => ':attribute phải là chuỗi !',
            'address.max' => ':attribute tối đa 255 ký tự !',
            'ward_id.required' => ':attribute không được để trống !',
            'ward_id.numeric' => ':attribute phải là số !',
            'ward_id.exists' => ':attribute phải là số !',
            'district_id.required' => ':attribute không được để trống !',
            'district_id.numeric' => ':attribute phải là số !',
            'district_id.exists' => ':attribute phải là số !',
            'province_id.required' => ':attribute không được để trống !',
            'province_id.numeric' => ':attribute phải là số !',
            'province_id.exists' => ':attribute phải là số !',
            'is_active.numeric' => ':attribute chưa đúng !',
        ];
    }

    protected function attributes(){
        return [
            'name' => 'Tên kho',
            'address' => 'Địa chỉ',
            'ward_id' => 'Xã/Phường/Thị trấn',
            'district_id' => 'Quận/Huyện',
            'province_id' => 'Tỉnh/Thành phố',
            'is_active' => 'Kích hoạt'
        ];
    }
}

?>
