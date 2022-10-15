<?php
use App\Http\Controllers\PostCategoryController;

Route::get('/v1/postcategories',[PostCategoryController::class,'index']);
Route::post('/v1/postcategories',[PostCategoryController::class,'store']);
Route::patch('/v1/postcategories/{id}',[PostCategoryController::class,'update']);
Route::delete('/v1/postcategories/{id}',[PostCategoryController::class,'destroy']);
Route::get('/v1/postcategories/{id}',[PostCategoryController::class,'show']);
