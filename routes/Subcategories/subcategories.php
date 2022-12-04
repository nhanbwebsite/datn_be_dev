<?php
// subcategories routes
use App\Http\Controllers\SubcategoryController;
<<<<<<< HEAD
Route::get('client/subcategories',[SubcategoryController::class,'getSubcateClients']);
Route::prefix('admin')->group(function (){
    Route::get('/subcategories',[SubcategoryController::class,'index']);
=======
Route::get('subcategories',[SubcategoryController::class,'index']);
Route::prefix('subcategories')->group(function (){
    Route::get('/',[SubcategoryController::class,'index']);
>>>>>>> 35425b554dfabcc5826fee0f5437372fea5370bd

    Route::post('/',[SubcategoryController::class,'store']);

    Route::get('/{id}',[SubcategoryController::class,'show']);

    Route::patch('/{id}',[SubcategoryController::class,'update']);

    Route::delete('/{id}',[SubcategoryController::class,'destroy']);
});

Route::get('client/subcategories/loadPost',[SubcategoryController::class,'loadAllPostByCate']);
Route::get('client/subcategories/loadPostBycate/{id}',[SubcategoryController::class,'loadPostByCate']);
