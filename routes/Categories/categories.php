<?php
use App\Http\Controllers\CategoryController;

Route::prefix('admin')->group(function(){
    // Route::get('/v1/categories',[CategoryController::class,'index']);
    Route::get('/categories',[CategoryController::class,'index']);
    Route::get('/categories_products',[CategoryController::class,'getCategoryProducts']);
    Route::get('/categories_post',[CategoryController::class,'getCategory_is_post']);

    Route::post('/categories',[CategoryController::class,'store']);

    Route::get('/categories/{id}',[CategoryController::class,'show']);

    Route::patch('/categories/{id}',[CategoryController::class,'update']);

    Route::delete('/categories/{id}',[CategoryController::class,'destroy']);
});

Route::get('/client/categories',[CategoryController::class,'getClientCategory']);
Route::get('/listSlideShowByCate',[CategoryController::class,'SlideShowByCate']);
