<?php

namespace App\Http\Validators\Promotion;

use App\Http\Validators\ValidatorBase;

class PromotionUpdateValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'name' => 'required|min:6|max:255',
            'expired_date' => 'required|date',
            'is_active' => 'numeric',
        ];
    }

    protected function messages(){
        return [
            'name.required' => ':attribute không được để trống !',
            'name.min' => ':attribute tối thiểu 6 ký tự !',
            'name.max' => ':attribute tối đa 255 ký tự !',
            'expired_date.required' => ':attribute không được để trống !',
            'expired_date.date' => ':attribute chưa đúng định dạng ngày tháng !',
            'is_active.numeric' => ':attribute chưa đúng định dạng số !',
        ];
    }

    protected function attributes(){
        return [
            'name' => 'Tên chương trình khuyến mãi',
            'expired_date' => 'Ngày hết hạn',
            'is_active' => 'Trạng thái',
        ];
    }
}
?>
