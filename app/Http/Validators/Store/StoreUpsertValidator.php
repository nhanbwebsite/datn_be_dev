<?php

namespace App\Http\Validators\Store;

use App\Http\Validators\ValidatorBase;

class StoreUpsertValidator extends ValidatorBase{
    protected function rules(){
        return [
            'name' => 'required|string|max:50',
            'slug' => 'string|max:255|unique_deleted_at_null:stores,slug',
            'address' => 'required|string|max:255',
            'ward_id' => 'required|numeric|exists:wards,id',
            'district_id' => 'required|numeric|exists:districts,id',
            'province_id' => 'required|numeric|exists:provinces,id',
            'is_active' => 'numeric',
        ];
    }

    protected function messages(){
        return [
            'slug.string' => ':attribute phải là chuỗi !',
            'slug.max' => ':attribute tối đa 50 ký tự !',
            'slug.unique_deleted_at_null' => ':attribute đã tồn tại !',
            'name.required' => ':attribute không được để trống !',
            'name.string' => ':attribute phải là chuỗi !',
            'name.max' => ':attribute tối đa 50 ký tự !',
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
            'is_active.numeric' => ':attribute phải là số !',
        ];
    }

    protected function attributes(){
        return [
            'name' => 'Tên cửa hàng',
            'slug' => 'Slug',
            'address' => 'Địa chỉ',
            'ward_id' => 'Xã/Phường/Thị trấn',
            'district_id' => 'Quận/Huyện',
            'province_id' => 'Tỉnh/Thành phố',
            'is_active' => 'Trạng thái'
        ];
    }
}

?>
