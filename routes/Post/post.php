<?php
use App\Http\Controllers\PostController;

    Route::prefix('posts')->middleware(['auth:sanctum'])->group(function (){
        Route::get('/',[PostController::class,'index']);
        Route::post('/',[PostController::class,'store']);
        Route::get('/{id}',[PostController::class,'show']);
        Route::put('/{id}',[PostController::class,'update']);
        Route::delete('/{id}',[PostController::class,'destroy']);
        Route::get('/statistical',[PostController::class,'statisticalTotalPost']);
    });
    Route::get('client/load-posts-by-view',[PostController::class,'loadByViews']);
    Route::get('client/load-post/',[PostController::class,'loadAllPostClient']);
    Route::get('client/load-post/{id}',[PostController::class,'loadDetailPostClient']);

    Route::get('client/get-firts-new-post',[PostController::class,'getFirtsNewPost']);
    Route::get('client/get-two-post-after-new',[PostController::class,'getTwoPostAfterNew']);
