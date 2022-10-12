<?php
use App\Http\Controllers\ProductController;

//  products routes

Route::get('/v1/products',[ProductController::class,'index']);
Route::get('/v1/product/{id}',[ProductController::class,'show']);
Route::post('/v1/products',[ProductController::class,'store']);
Route::patch('/v1/product/{id}',[ProductController::class,'update']);
Route::delete('/v1/product/{id}',[ProductController::class,'destroy']);

Route::get('/v1/postcategories',[PostCategoryController::class,'index']);
Route::post('/v1/postcategories',[PostCategoryController::class,'store']);
Route::patch('/v1/postcategories/{id}',[PostCategoryController::class,'update']);
Route::delete('/v1/postcategories/{id}',[PostCategoryController::class,'destroy']);
Route::get('/v1/postcategories/{id}',[PostCategoryController::class,'show']);
