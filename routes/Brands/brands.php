<?php
use App\Http\Controllers\BrandController;
//  Brands routes

Route::prefix('admin')->group(function(){
    Route::get('/v1/brands/',[BrandController::class,'index']);
    Route::get('/v1/brands/{id}',[BrandController::class,'show']);
    Route::patch('/v1/brands/{id}',[BrandController::class,'show']);
    Route::delete('/v1/brands/{id}',[BrandController::class,'destroy']);
});
