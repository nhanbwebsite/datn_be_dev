<?php
// subcategories routes
use App\Http\Controllers\SubcategoryController;
Route::get('subcategories',[SubcategoryController::class,'index']);
Route::prefix('subcategories')->group(function (){
    Route::get('/',[SubcategoryController::class,'index']);

    Route::post('/',[SubcategoryController::class,'store']);

    Route::get('/{id}',[SubcategoryController::class,'show']);

    Route::patch('/{id}',[SubcategoryController::class,'update']);

    Route::delete('/{id}',[SubcategoryController::class,'destroy']);
});

Route::get('client/subcategories/loadPost',[SubcategoryController::class,'loadAllPostByCate']);
Route::get('client/subcategories/loadPostBycate/{id}',[SubcategoryController::class,'loadPostByCate']);
