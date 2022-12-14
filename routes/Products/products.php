<?php
use App\Http\Controllers\ProductController;

//  products routes

Route::prefix('products')->middleware('auth:sanctum')->group(function (){

    Route::get('/{id}',[ProductController::class,'show']);
    Route::post('/',[ProductController::class,'store']);
    Route::patch('/update/{id}',[ProductController::class,'update']);
    Route::delete('/{id}',[ProductController::class,'destroy']);
    Route::get('/',[ProductController::class,'index']);

    // delete variant of product
});

Route::prefix('variant_of_product')->middleware('auth:sanctum')->group(function (){

    Route::get('/{id}',[ProductController::class,'getVariantById']);
    Route::post('/',[ProductController::class,'store']);
    Route::patch('/{id}',[ProductController::class,'update']);
    Route::delete('/{id}',[ProductController::class,'deleteVariantOfproduct']);

    // delete variant of product
});
// public route products
Route::get('/client/products/{id}',[ProductController::class,'show']);
Route::get('/client/products',[ProductController::class,'productsAllForClient']);

Route::get('findProductByStore',[ProductController::class,'productByStore']);
Route::get('productsBySubcate/{subId}',[ProductController::class,'producstBySubcategoryId']);

Route::get('client/products/category/{id}',[ProductController::class,'productsByCategoryId']);

// tìm sản phẩm
Route::get('client_products_search/',[ProductController::class,'search']);

// province by store, products < 0
Route::get('client/productsfindbylocation',[ProductController::class,'getProvincesByWarehouse']);

Route::get('client/getallproductbysubcategories',[ProductController::class,'getAllSubcate']);

//  phân quyền admin
Route::get('/productsHaveComment',[ProductController::class,'productsHaveCommentAll']);
Route::get('/getproductByWarehouse',[ProductController::class,'checkProductsAmount']);

