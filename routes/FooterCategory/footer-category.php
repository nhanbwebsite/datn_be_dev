<?php
use App\Http\Controllers\FooterCategoryController;

    Route::prefix('footer-category')->middleware(['auth:sanctum'])->group(function (){
        // Trả dữ liễu có phân trang
        Route::get('/',[FooterCategoryController::class,'index']);
        //Thêm
        Route::post('/',[FooterCategoryController::class,'store']);
        //lấy dữ liệu theo id
        Route::get('/{id}',[FooterCategoryController::class,'show']);
        //Cập nhật
        Route::put('/{id}',[FooterCategoryController::class,'update']);
        //Xóa
        Route::delete('/{id}',[FooterCategoryController::class,'destroy']);
        //Trả dữ liệu con theo id dữ liêuk
        Route::get('load-by-cate/{id}',[FooterCategoryController::class,'loadByCate']);



    });
        //Trả tất cả dữ liệu không phân trang bao gồm dữ liệu con
    Route::get('/client/footer-category/load-all',[FooterCategoryController::class,'loadAllByCate']);
     //Trả về dữ liệu con của danh mục là is_contact

    Route::get('/client/footer-category/categories_contact',[FooterCategoryController::class,'getCategory_is_contact']);
