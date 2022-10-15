<?php
use App\Http\Controllers\ProductController;

//  products routes

Route::prefix('admin')->group(function (){
    Route::get('/v1/products',[ProductController::class,'index']);
    Route::get('/v1/product/{id}',[ProductController::class,'show']);
    Route::post('/v1/products',[ProductController::class,'store']);
    Route::patch('/v1/product/{id}',[ProductController::class,'update']);
    Route::delete('/v1/product/{id}',[ProductController::class,'destroy']);
});


