<?php
use App\Http\Controllers\ProductController;

//  products routes

Route::prefix('admin')->group(function (){
    Route::get('/v1/products',[ProductController::class,'index']);
    Route::get('/v1/products/{id}',[ProductController::class,'show']);
    Route::post('/v1/products',[ProductController::class,'store']);
    Route::patch('/v1/products/{id}',[ProductController::class,'update']);
    Route::delete('/v1/products/{id}',[ProductController::class,'destroy']);
});


Route::get('findProductByStore',[ProductController::class,'productByStore']);
