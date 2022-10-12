<?php
// subcategories routes
use App\Http\Controllers\SubcategoryController;
Route::get('/v1/subcategories',[SubcategoryController::class,'index']);

Route::post('/v1/subcategories',[SubcategoryController::class,'store']);

Route::get('/v1/subcategories/{id}',[SubcategoryController::class,'show']);

Route::patch('/v1/subcategories/{id}',[SubcategoryController::class,'update']);

Route::delete('/v1/subcategories/{id}',[SubcategoryController::class,'destroy']);
