<?php

namespace App\Http\Validators\Cart;

use App\Http\Validators\ValidatorBase;

class CartCreateValidator extends ValidatorBase
{

    protected function rules(){
        return [
            'user_id' => 'required|numeric|exists:users,id',
            'address_note_id' => 'required|numeric|exists:address_notes,id',
            'coupon_id' => 'nullable|numeric',
            'promotion_id' => 'nullable|numeric',
            'discount' => 'required|numeric',
            'fee_ship' => 'required|numeric',
            'details' => 'required',
        ];
    }

    protected function messages(){
        return [
            'user_id.required' => ':attribute không được để trống !',
            'user_id.numeric' => ':attribute phải là số !',
            'user_id.exists' => ':attribute không tồn tại !',
            'address_note_id.required' => ':attribute không được để trống !',
            'address_note_id.numeric' => ':attribute phải là số !',
            'address_note_id.exists' => ':attribute không tồn tại !',
            'product_id.required' => ':attribute không được để trống !',
            'product_id.numeric' => ':attribute phải là số !',
            'product_id.exists' => ':attribute không tồn tại !',
            'price.required' => ':attribute không được để trống !',
            'price.numeric' => ':attribute phải là số !',
            'quantity.required' => ':attribute không được để trống !',
            'quantity.numeric' => ':attribute phải là số !',
            'coupon_id.numeric' => ':attribute phải là số !',
            'promotion_id.numeric' => ':attribute phải là số !',
            'discount.required' => ':attribute không được để trống !',
            'discount.numeric' => ':attribute phải là số !',
            'fee_ship.required' => ':attribute không được để trống !',
            'fee_ship.numeric' => ':attribute phải là số !',
            'details.required' => ':attribute không được để trống !',
        ];
    }

    protected function attributes(){
        return [
            'user_id' => 'Mã người dùng',
            'address_note_id' => 'Mã sổ địa chỉ',
            'product_id' => 'Mã sản phẩm',
            'price' => 'Giá bán',
            'quantity' => 'Số lượng mua',
            'coupon_id' => 'Mã giảm giá',
            'promotion_id' => 'Mã chương trình khuyến mãi',
            'discount' => 'Tiền giảm',
            'fee_ship' => 'Phí ship',
            'details' => 'Chi tiết',
        ];
    }
}

?>
