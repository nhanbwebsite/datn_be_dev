<?php
use App\Http\Controllers\ProductVariant;

Route::prefix('product_variants')->middleware('auth:sanctum')->group(function (){
    Route::get('/',[ProductVariant::class,'index']);
    Route::get('/{id}',[ProductVariant::class,'show']);
    Route::post('/',[ProductVariant::class,'store']);
    Route::patch('/{id}',[ProductVariant::class,'update']);
    Route::delete('/{id}',[ProductVariant::class,'destroy']);
});