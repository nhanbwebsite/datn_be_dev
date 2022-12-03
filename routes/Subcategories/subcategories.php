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
