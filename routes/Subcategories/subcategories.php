<?php
// subcategories routes
use App\Http\Controllers\SubcategoryController;
Route::get('client/subcategories',[SubcategoryController::class,'getSubcateClients']);
Route::prefix('admin')->group(function (){
    Route::get('/subcategories',[SubcategoryController::class,'index']);

    Route::post('/subcategories',[SubcategoryController::class,'store']);

    Route::get('/subcategories/{id}',[SubcategoryController::class,'show']);

    Route::patch('/subcategories/{id}',[SubcategoryController::class,'update']);

    Route::delete('/subcategories/{id}',[SubcategoryController::class,'destroy']);
});
