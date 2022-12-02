<?php
use App\Http\Controllers\BrandController;
//  Brands routes

Route::prefix('admin')->group(function(){
    Route::get('brands/',[BrandController::class,'index']);
    Route::post('brands/',[BrandController::class,'store']);
    Route::get('brands/{id}',[BrandController::class,'show']);
    Route::patch('brands/{id}',[BrandController::class,'show']);
    Route::delete('brands/{id}',[BrandController::class,'destroy']);
});
