<?php

namespace App\Http\Validators\Order;

use App\Http\Validators\ValidatorBase;

class ApproveOrderValidator extends ValidatorBase
{
    protected function rules(){
        return [
            // 'order_code' => 'required|string|exists:orders,code',
            // 'status' => 'required|numeric|exists:order_status,id',
            'warehouse_id' => 'required|numeric|exists:warehouses,id',
        ];
    }

    protected function messages(){
        return [
            // 'order_code.required' => ':attribute không được để trống !',
            // 'order_code.string' => ':attribute phải là chuỗi !',
            // 'order_code.exists' => ':attribute không tồn tại !',
            // 'status.required' => ':attribute không được để trống !',
            // 'status.numeric' => ':attribute phải là số !',
            // 'status.exists' => ':attribute không tồn tại !',
            'warehouse_id.required' => ':attribute không được để trống !',
            'warehouse_id.numeric' => ':attribute phải là số !',
            'warehouse_id.exists' => ':attribute không tồn tại !',
        ];
    }

    protected function attributes(){
        return [
            // 'order_code' => 'Mã đơn hàng',
            // 'status' => 'Trạng thái đơn hàng',
            'warehouse_id' => 'ID kho nhận đơn',
        ];
    }
}

?>
