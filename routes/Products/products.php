<?php
use App\Http\Controllers\ProductController;

//  products routes

Route::prefix('products')->middleware('auth:sanctum')->group(function (){

    Route::get('/{id}',[ProductController::class,'show']);
    Route::post('/',[ProductController::class,'store']);
    Route::put('/{id}',[ProductController::class,'update']);
    Route::delete('/{id}',[ProductController::class,'destroy']);
});
// public route products
Route::get('/client/products/{id}',[ProductController::class,'show']);
Route::get('/client/products',[ProductController::class,'index']);

Route::get('findProductByStore',[ProductController::class,'productByStore']);
Route::get('productsBySubcate/{subId}',[ProductController::class,'producstBySubcategoryId']);

Route::get('client/products/category/{id}',[ProductController::class,'productsByCategoryId']);
