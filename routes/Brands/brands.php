<?php
use App\Http\Controllers\BrandController;
//  Brands routes

Route::prefix('admin')->group(function(){
    Route::get('brands/',[BrandController::class,'index']);
    Route::post('brands/',[BrandController::class,'store']);
    Route::get('brands/{id}',[BrandController::class,'show']);
    Route::patch('brands/{id}',[BrandController::class,'update']);
    Route::delete('brands/{id}',[BrandController::class,'destroy']);
    Route::get('brands-not-paginate/',[BrandController::class,'brandnotPaginate']);
});

Route::prefix('brand_post')->group(function(){
    Route::get('/',[BrandController::class,'brand_post']);

});
