<?php

namespace App\Http\Validators\Product;

use App\Http\Validators\ValidatorBase;

class ProductCreateValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'code' => 'max:50|unique_deleted_at_null:products,code',
            'name' => 'required|min:6|max:255',
            'slug' => 'nullable|min:6|max:255',
            'description' => 'nullable',
            'url_image' => 'required|url',
            // 'price' => 'numeric|min:0',
            // 'discount' => 'numeric|min:0',
            'specification_infomation' => 'nullable',

            'subcategory_id' => 'required|numeric',
            'is_active' => 'numeric',
        ];
    }

    protected function messages(){
        return [
            'code.max' => ':attribute tối đa 50 ký tự !',
            'code.unique_deleted_at_null' => ':attribute đã tồn tại !',
            'name.required' => ':attribute không được để trống !',
            'name.min' => ':attribute tối thiểu 6 ký tự !',
            'name.max' => ':attribute tối đa 255 ký tự !',
            'slug.required' => ':attribute không được để trống !',
            'slug.min' => ':attribute tối thiểu 6 ký tự !',
            'slug.max' => ':attribute tối đa 255 ký tự !',
            'url_image.required' => ':attribute không được để trống !',
            'url_image.url' => ':attribute chưa đúng định dạng URL !',
            // 'price.required' => ':attribute không được để trống !',
            // 'price.min' => ':attribute tối thiểu là 0 !',
            // 'discount.required' => ':attribute không được để trống !',
            // 'discount.min' => ':attribute tối thiểu là 0 !',

            'subcategory_id.required' => ':attribute không được để trống !',
            'subcategory_id.numeric' => ':attribute chưa đúng định dạng số !',
            // 'product_variant_name.required' => ':attribute không không được để trống'
        ];
    }

    protected function attributes(){
        return [
            'name' => 'Tên sản phẩm',
            'slug' => 'Slug',
            'description' => 'Mô tả sản phẩm',
            'url_image' => 'Ảnh sản phẩm',
            // 'price' => 'Giá bán',
            // 'discount' => 'Giảm giá',
            'specification_infomation' => 'Thông số kỹ thuật',
            'subcategory_id' => 'Mã danh mục',
            'is_active' => 'Trạng thái',
            // 'product_variant_name' => 'Biến thể'
        ];
    }
}
?>
