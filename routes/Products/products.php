<?php
use App\Http\Controllers\ProductController;

//  products routes

Route::prefix('products')->middleware('auth:sanctum')->group(function (){
    Route::get('/',[ProductController::class,'index']);
    Route::get('/{id}',[ProductController::class,'show']);
    Route::post('/',[ProductController::class,'store']);
    Route::put('/{id}',[ProductController::class,'update']);
    Route::delete('/{id}',[ProductController::class,'destroy']);
});


Route::get('findProductByStore',[ProductController::class,'productByStore']);
Route::get('productsBySubcate/{subId}',[ProductController::class,'producstBySubcategoryId']);
