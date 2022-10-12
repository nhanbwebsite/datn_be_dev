<?php
use App\Http\Controllers\CategoryController;


Route::get('/v1/categories',[CategoryController::class,'index']);

Route::post('/v1/categories',[CategoryController::class,'store']);

Route::get('/v1/categories/{id}',[CategoryController::class,'show']);

Route::patch('/v1/categories/{id}',[CategoryController::class,'update']);

Route::delete('/v1/categories/{id}',[CategoryController::class,'destroy']);
