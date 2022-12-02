<?php
use App\Http\Controllers\FooterCategoryController;

    Route::prefix('footer-category')->middleware(['auth:sanctum'])->group(function (){
        Route::get('/',[FooterCategoryController::class,'index']);
        Route::post('/',[FooterCategoryController::class,'store']);
        Route::get('/{id}',[FooterCategoryController::class,'show']);
        Route::put('/{id}',[FooterCategoryController::class,'update']);
        Route::delete('/{id}',[FooterCategoryController::class,'destroy']);
        Route::get('load-by-cate/{id}',[FooterCategoryController::class,'loadByCate']);
    });
    Route::get('/client/footer-category/load-all',[FooterCategoryController::class,'loadAllByCate']);
