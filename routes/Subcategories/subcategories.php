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
    Route::get('subcategoriesIsPosts',[SubcategoryController::class,'getSubcatePosts']);
    Route::get('subcategoriesProducts',[SubcategoryController::class,'getSubcateproducts']);
});
    Route::get('client/subcategories/loadPostByCate/{id}',[SubcategoryController::class,'loadByCate']);

    Route::get('client/subcategories/load-view-by-cate/{id}',[SubcategoryController::class,'loadPostByViewOfCate']);

    Route::get('client/subcategories/get-firts-new-post-by-cate/{id}',[SubcategoryController::class,'getFirtsNewPostByCate']);

    Route::get('client/subcategories/get-two-post-after-new/{id}',[SubcategoryController::class,'getTwoPostAfterNew']);
